<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class UpdateModelIdInSpatiePermissions extends Migration
{
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // تعديل جدول model_has_roles
        Schema::table('model_has_roles', function (Blueprint $table) {
            // حذف المفتاح الأساسي القديم
            $table->dropPrimary(['role_id', 'model_id', 'model_type']);

            // تعديل model_id إلى UUID
            $table->uuid('model_id')->change();

            // إعادة تعيين المفتاح الأساسي
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // تعديل جدول model_has_permissions
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropPrimary(['permission_id', 'model_id', 'model_type']);
            $table->uuid('model_id')->change();
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // إعادة التغييرات إلى الوضع الأصلي
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropPrimary(['role_id', 'model_id', 'model_type']);
            $table->unsignedBigInteger('model_id')->change();
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropPrimary(['permission_id', 'model_id', 'model_type']);
            $table->unsignedBigInteger('model_id')->change();
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
