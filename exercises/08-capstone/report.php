<?php


declare(strict_types=1);
require_once __DIR__ . "/../06-strings/slugify.php";

$transactions = [
    ['id' => 1, 'user' => 'alice',   'amount' => 42.50,  'category' => 'Books & Media',     'ts' => '2026-04-18 10:15:00', 'refunded' => false],
    ['id' => 2, 'user' => 'bob',     'amount' => 19.99,  'category' => 'Home Goods',        'ts' => '2026-04-18 11:40:00', 'refunded' => true],
    ['id' => 3, 'user' => 'alice',   'amount' => 120.00, 'category' => 'Electronics',       'ts' => '2026-04-19 09:00:00', 'refunded' => false],
    ['id' => 4, 'user' => 'carol',   'amount' => 8.00,   'category' => 'Books & Media',     'ts' => '2026-04-19 12:30:00', 'refunded' => false],
    ['id' => 5, 'user' => 'dave',    'amount' => 250.00, 'category' => 'Electronics',       'ts' => '2026-04-20 14:00:00', 'refunded' => false],
    ['id' => 6, 'user' => 'bob',     'amount' => 15.00,  'category' => 'Home Goods',        'ts' => '2026-04-20 16:45:00', 'refunded' => false],
    ['id' => 7, 'user' => 'erin',    'amount' => 99.00,  'category' => 'Electronics',       'ts' => '2026-04-21 08:20:00', 'refunded' => true],
    ['id' => 8, 'user' => 'frank',   'amount' => 5.50,   'category' => 'Books & Media',     'ts' => '2026-04-21 19:10:00', 'refunded' => false],
];


function dateOnly(string $timestamp): string
{
    return substr($timestamp, 0, 10);
}


## Finding Period
$mappingTs = array_map(function ($data) {
    return $data["ts"];
}, $transactions);

// mapping from and to
$tsFrom = dateOnly(min($mappingTs));
$tsTo = dateOnly(max($mappingTs));


## Completed Transactions
$completed = count(array_filter($transactions, fn($data) => !$data["refunded"]));

## Refunded Transactions
$refunded = count(array_filter($transactions, fn($data) => $data["refunded"]));


function totalsByCategory(array $txs): array
{
    $mappedTs = [];
    foreach ($txs as $ts) {
        $ts["category"] = slugify($ts["category"]);
        $mappedTs[] = $ts;
    }

    $result = array_reduce($mappedTs, function ($carry, $ts) {

        if (!isset($carry[$ts["category"]])) {
            $carry[$ts["category"]] = 0;
        }

        $carry[$ts["category"]] += $ts["amount"];
        return $carry;
    }, []);

    ## optional optimization:
    // return array_reduce($txs, function ($carry, $tx) {
    //     $cat = slugify($tx["category"]);
    //     $carry[$cat] = ($carry[$cat] ?? 0) + (float)$tx["amount"];
    //     return $carry;
    // }, []);

    return $result;
}

$completedTxs = array_filter($transactions, fn($tx) => !$tx["refunded"]);
$transactionsTotalsByCategory = totalsByCategory($completedTxs);
function formatReport(array $totals, int $completed, int $refunded, string $from, string $to): string
{
    $header = "=== Daily Revenue Report ===";
    $period = "Period: $from to $to";
    $completedTransactions = "Completed transactions : $completed";
    $refundedTransactions = "Refunded transactions : $refunded";
    $revenueByCategory = "Revenue by category:\n";
    $totalRevenue = 0;
    foreach ($totals as $tts => $value) {
        $label = str_pad($tts, 20, '.', STR_PAD_RIGHT);
        $formattedValue = number_format($value, 2);
        $revenueByCategory .=  "- $label $$formattedValue\n";
        $totalRevenue += $value;
    }
    $formattedTotalRevenue = number_format($totalRevenue, 2);
    $totalRevenueMsg = "Total revenue: $$formattedTotalRevenue";


    return "$header\n$period\n\n$completedTransactions\n$refundedTransactions\n$revenueByCategory\n$totalRevenueMsg";
}

$lines = explode("\n", formatReport($transactionsTotalsByCategory, $completed, $refunded, $tsFrom, $tsTo));

echo implode(PHP_EOL, $lines);
// echo formatReport($transactions), PHP_EOL;
