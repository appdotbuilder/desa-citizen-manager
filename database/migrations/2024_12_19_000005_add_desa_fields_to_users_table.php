<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('desa_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->enum('role', ['super_admin', 'admin_desa', 'kepala_desa', 'ketua_rw', 'ketua_rt', 'warga'])->default('warga')->after('password');
            $table->string('nik', 16)->nullable()->after('email');
            $table->string('no_kk', 16)->nullable()->after('nik');
            $table->date('birth_date')->nullable()->after('no_kk');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->enum('gender', ['L', 'P'])->nullable()->after('birth_place');
            $table->enum('religion', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu'])->nullable()->after('gender');
            $table->enum('marital_status', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'])->nullable()->after('religion');
            $table->string('occupation')->nullable()->after('marital_status');
            $table->enum('education', ['tidak_sekolah', 'sd', 'smp', 'sma', 'diploma', 'sarjana', 'magister', 'doktor'])->nullable()->after('occupation');
            $table->text('address')->nullable()->after('education');
            $table->foreignId('rt_id')->nullable()->after('address')->constrained()->nullOnDelete();
            $table->foreignId('rw_id')->nullable()->after('rt_id')->constrained()->nullOnDelete();
            $table->string('phone', 20)->nullable()->after('rw_id');
            $table->json('documents')->nullable()->after('phone');
            $table->enum('citizen_status', ['active', 'moved', 'deceased', 'inactive'])->default('active')->after('documents');
            
            // Add indexes for tenant isolation and performance
            $table->index('desa_id');
            $table->index(['desa_id', 'role']);
            $table->index(['desa_id', 'citizen_status']);
            $table->index(['desa_id', 'rt_id']);
            $table->index(['desa_id', 'rw_id']);
            $table->index('nik');
            $table->index('no_kk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropForeign(['rt_id']);
            $table->dropForeign(['rw_id']);
            $table->dropColumn([
                'desa_id', 'role', 'nik', 'no_kk', 'birth_date', 'birth_place', 
                'gender', 'religion', 'marital_status', 'occupation', 'education', 
                'address', 'rt_id', 'rw_id', 'phone', 'documents', 'citizen_status'
            ]);
        });
    }
};