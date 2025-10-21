<?php

namespace App\Traits;

trait HasBaseModel
{
  public function mediaExists($key)
  {
    return (bool) $this->getFirstMediaUrl($key);
  }

  public function isTrue($key)
  {
    return (bool) $this->{$key}?->value;
  }

  public function scopeDateRange($q, $from = null, $to = null, $column = 'created_at')
  {
    try {
      if ($from) {
        $from = carbon($from)->format('Y-m-d');
      }
      if ($to) {
        $to = carbon($to)->addDay()->format('Y-m-d');
      }
    } catch (\Exception $e) {
      return $q;
    }

    $column = $this->getTable() . '.' . $column;

    $q->when($from, fn($q) => $q->where($column, '>=', $from));
    $q->when($to, fn($q) => $q->where($column, '<', $to));
  }
}