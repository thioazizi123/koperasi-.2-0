<?php

use App\Models\Saving;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pokok = Saving::where('type', 'pokok')->sum('amount');
$wajib = Saving::where('type', 'wajib')->sum('amount');
$others = Saving::whereNotIn('type', ['pokok', 'wajib'])->get();
$othersSum = $others->sum('amount');

echo "Pokok: $pokok\n";
echo "Wajib: $wajib\n";
echo "Others Sum: $othersSum\n";
echo "Others Detail:\n";
foreach ($others as $other) {
    echo "- ID: {$other->id}, Type: {$other->type}, Amount: {$other->amount}, Date: {$other->transaction_date}\n";
}

$allSavings = Saving::whereIn('type', ['pokok', 'wajib'])->get();
echo "\nDetail Pokok & Wajib:\n";
foreach ($allSavings as $s) {
    echo "- ID: {$s->id}, Type: {$s->type}, Amount: {$s->amount}, Member: {$s->member_id}\n";
}
