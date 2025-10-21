<?php

namespace App\Traits;

trait HasTranslationModel
{
    public function scopeJoinTranslation($q, $table = null, $translation_table = null, $foreign_key = null, $id = 'id')
    {
        $table ??= with(new static)->getTable();
        $translation_table ??= str($table)->singular() . "_translations";
        $foreign_key ??= str($table)->singular() . "_id";

        return $q->leftJoin("$translation_table", "$table.$id", "=", "$translation_table.$foreign_key")
        ->where("$translation_table.locale", app()->getLocale());
    }

    public static function list($name = 'name', $id = 'id')
    {
        return self::with('translations')->get()->pluck($name, $id);
    }
}
