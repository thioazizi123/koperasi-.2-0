<?php

use App\Models\Member;
use Illuminate\Support\Facades\DB;

// Load Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Updating Member IDs...\n";

$members = Member::where('member_no', 'LIKE', '%/IKAP/%')->get();
$count = 0;

foreach ($members as $member) {
    $oldNo = $member->member_no;
    $newNo = str_replace('/IKAP/', '/IKAB/', $oldNo);

    $member->member_no = $newNo;
    $member->saveQuietly(); // Use saveQuietly to avoid triggering the created event or other observers

    echo "Updated: $oldNo -> $newNo\n";
    $count++;
}

echo "Done. Updated $count members.\n";
