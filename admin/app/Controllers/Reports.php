<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;
use App\Models\LeadModel;
use CodeIgniter\Controller;

class Reports extends Controller
{
    public function index()
    {
        [$dateFrom, $dateTo] = $this->resolveViewRange(
            $this->request->getGet('date_from'),
            $this->request->getGet('date_to')
        );

        $leadModel    = new LeadModel();
        $contactModel = new ContactMessageModel();

        $totalLeads       = $leadModel->totalInRange($dateFrom, $dateTo);
        $totalContacts    = $contactModel->totalInRange($dateFrom, $dateTo);
        $leadStatusCounts = $leadModel->countByStatusInRange($dateFrom, $dateTo);
        $approvedLeads    = $leadStatusCounts['approved'] ?? 0;
        $approvalRate     = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 1) : 0.0;
        $avgLoanAmount    = $leadModel->averageLoanAmountInRange($dateFrom, $dateTo);

        // Daily buckets keep each point meaningful for ranges of 31 days or less; beyond
        // that, monthly buckets keep the trend chart readable instead of a long, noisy
        // run of daily points.
        $rangeDays   = (int) floor((strtotime($dateTo) - strtotime($dateFrom)) / 86400) + 1;
        $granularity = $rangeDays <= 31 ? 'day' : 'month';
        $leadsTrend  = $leadModel->trendInRange($dateFrom, $dateTo, $granularity);

        $data = [
            'title' => 'Reports',

            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,

            'totalLeads'    => $totalLeads,
            'totalContacts' => $totalContacts,
            'approvedLeads' => $approvedLeads,
            'approvalRate'  => $approvalRate,
            'avgLoanAmount' => $avgLoanAmount,

            'leadsTrend'       => $leadsTrend,
            'trendGranularity' => $granularity,

            'leadStatusCounts'      => $leadStatusCounts,
            'leadStatusLabels'      => LeadModel::STATUSES,
            'leadsByLoanType'       => $leadModel->countByLoanTypeInRange($dateFrom, $dateTo),
            'leadsByEmploymentType' => $leadModel->countByEmploymentTypeInRange($dateFrom, $dateTo),

            'contactStatusCounts' => $contactModel->countByStatusInRange($dateFrom, $dateTo),
            'contactStatusLabels' => ContactMessageModel::STATUSES,
        ];

        return view('reports/index', $data);
    }

    public function exportLeadsCsv()
    {
        [$dateFrom, $dateTo] = $this->resolveExportRange(
            $this->request->getGet('date_from'),
            $this->request->getGet('date_to')
        );

        $rows = (new LeadModel())->forExport($dateFrom, $dateTo);

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, [
            'Full Name', 'Mobile', 'Email', 'Loan Type', 'Loan Amount',
            'Employment Type', 'City', 'Status', 'Message', 'Submitted At',
        ]);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $this->csvSafe($row['full_name']),
                $this->csvSafe($row['mobile']),
                $this->csvSafe($row['email']),
                $this->csvSafe($row['loan_type']),
                $this->csvSafe($row['loan_amount']),
                $this->csvSafe($row['employment_type']),
                $this->csvSafe($row['city']),
                $this->csvSafe(LeadModel::STATUSES[$row['status']] ?? $row['status']),
                $this->csvSafe($row['message']),
                $this->csvSafe(date('Y-m-d H:i:s', strtotime($row['created_at']))),
            ]);
        }

        return $this->streamCsv($handle, 'finzo-leads-' . date('Y-m-d') . '.csv');
    }

    public function exportContactsCsv()
    {
        [$dateFrom, $dateTo] = $this->resolveExportRange(
            $this->request->getGet('date_from'),
            $this->request->getGet('date_to')
        );

        $rows = (new ContactMessageModel())->forExport($dateFrom, $dateTo);

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Full Name', 'Mobile', 'Email', 'Message', 'Status', 'Submitted At']);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $this->csvSafe($row['full_name']),
                $this->csvSafe($row['mobile']),
                $this->csvSafe($row['email']),
                $this->csvSafe($row['message']),
                $this->csvSafe(ContactMessageModel::STATUSES[$row['status']] ?? $row['status']),
                $this->csvSafe(date('Y-m-d H:i:s', strtotime($row['created_at']))),
            ]);
        }

        return $this->streamCsv($handle, 'finzo-contacts-' . date('Y-m-d') . '.csv');
    }

    /**
     * Build the CI4 CSV download response from an in-memory (php://temp) file handle.
     * Building the string in memory first (rather than writing straight to php://output
     * and fighting CI4's own output buffering) reliably produces a correctly-framed
     * response body regardless of buffering state.
     */
    private function streamCsv($handle, string $filename)
    {
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        // UTF-8 BOM so Excel opens names/messages with non-ASCII characters correctly.
        $csv = "\xEF\xBB\xBF" . $csv;

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    /**
     * Neutralize CSV/formula injection: a cell starting with =, +, - or @ can be read as
     * a formula by Excel/Sheets. Prefixing with a leading apostrophe forces it to be read
     * as plain text while keeping the visible value intact for the user.
     */
    private function csvSafe($value): string
    {
        $value = (string) $value;
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'], true)) {
            return "'" . $value;
        }
        return $value;
    }

    /**
     * Resolve the Reports page's date range. Neither param given -> default to the last
     * 30 days (shown pre-filled in the date inputs). Only one given -> derive the other
     * so the range always has two real bounds. Swapped/invalid input is corrected rather
     * than erroring.
     */
    private function resolveViewRange(?string $rawFrom, ?string $rawTo): array
    {
        $from = $this->parseDate($rawFrom);
        $to   = $this->parseDate($rawTo);

        if ($from === null && $to === null) {
            $to   = date('Y-m-d');
            $from = date('Y-m-d', strtotime('-29 days'));
        } elseif ($from === null) {
            $from = date('Y-m-d', strtotime($to . ' -29 days'));
        } elseif ($to === null) {
            $to = date('Y-m-d');
        }

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        return [$from, $to];
    }

    /**
     * Resolve the CSV export date range. Unlike the view, when NEITHER param is supplied
     * we export everything ever recorded (null/null = unbounded in the model) rather than
     * silently defaulting to 30 days - a bare "download the CSV" link is more likely to
     * mean "give me everything" than a specific window, and defaulting would produce a
     * confusingly partial (or empty) file. Any date that IS supplied is still honoured.
     */
    private function resolveExportRange(?string $rawFrom, ?string $rawTo): array
    {
        $from = $this->parseDate($rawFrom);
        $to   = $this->parseDate($rawTo);

        if ($from === null && $to === null) {
            return [null, null];
        }

        if ($from !== null && $to !== null && $from > $to) {
            [$from, $to] = [$to, $from];
        }

        return [$from, $to];
    }

    /** Parse a 'Y-m-d' date string, returning null for empty/malformed input. */
    private function parseDate(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $date = \DateTime::createFromFormat('Y-m-d', $value);
        if (! $date || $date->format('Y-m-d') !== $value) {
            return null;
        }

        return $value;
    }
}
