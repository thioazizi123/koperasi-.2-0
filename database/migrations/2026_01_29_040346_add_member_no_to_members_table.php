<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('member_no')->nullable()->unique()->after('id');
        });

        // Backfill existing members
        $members = \Illuminate\Support\Facades\DB::table('members')->get();
        foreach ($members as $member) {
            $joinDate = \Carbon\Carbon::parse($member->join_date);
            $memberNo = sprintf('%03d/IKAP/%s/%s', $member->id, $joinDate->format('m'), $joinDate->format('Y'));

            \Illuminate\Support\Facades\DB::table('members')
                ->where('id', $member->id)
                ->update(['member_no' => $memberNo]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('member_no');
        });
    }
};
