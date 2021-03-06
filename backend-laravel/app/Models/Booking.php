<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
      'room_id',
      'start_date',
      'end_date',
      'customer_id',
    ];
    const VALIDATOR_OPTIONS = [
      'room_id' => 'required|integer|min:1',
      'start_date' => 'required|date',
      'end_date' => 'required|date',
      'customer_id' => 'required|integer|min:1',
    ];

    const VALIDATOR_OPTIONS_BOOKING = [
      'room.id' => 'required|integer|min:1',
      'start_date' => 'required|date',
      'end_date' => 'required|date',
      'customer.id' => 'required',
      'customer.first_name' => 'required',
      'customer.last_name' => 'required',
      'customer.phone' => 'required',
      'customer.email' => 'required',
    ];


    //scopes "filters"
    public function scopeYear($query, $scope){
      if (!empty($scope)) {
        $query->where(function($query)use($scope){
          $query->whereYear('start_date', '=',  $scope)->
          whereYear('end_date', '=', $scope, 'or');
        });
        
      }
    }
    public function scopeMonth($query, $scope){
      if (!empty($scope)) {
        $query->where(function($query)use($scope){
          $query->whereMonth('start_date', '=',  $scope)->
                  whereMonth('end_date', '=', $scope, 'or');
        });
      }
    }
    public function scopeDate($query, $scope){
      if (!empty($scope)) {
        $query->where('start_date', '<=', $scope);
        $query->where('end_date', '>=', $scope);
      }
    }
    public function scopeStartDate($query, $scope){
      if (!empty($scope)) {
        $query->where('start_date', '>=', $scope);
      }
    }
    public function scopeEndDate($query, $scope){
      if (!empty($scope)) {
        $query->where('end_date', '<=', $scope);
      }
    }
    public function scopeCustomerId($query, $scope){
      if (!empty($scope)) {
        $query->where('customer_id', $scope);
      }
    }
    public function scopeRoomId($query, $scope){
      if (!empty($scope)) {
        $query->where('room_id', $scope);
      }
    }

    public static function filtered($datos){
      return self::startDate(isset($datos['start_date'])?$datos['start_date']:null)
      ->endDate(isset($datos['end_date'])?$datos['end_date']:null)
      ->date(isset($datos['date'])?$datos['date']:null)
      ->customerId(isset($datos['customer_id'])?$datos['customer_id']:null)
      ->roomId(isset($datos['room_id'])?$datos['room_id']:null)
      ->month(isset($datos['month'])?$datos['month']:null)
      ->year(isset($datos['year'])?$datos['year']:null);
    }

    public static function filterAndPaginate($datos){
      return self::filtrado($datos)
      ->paginate(10);
    }
    //!scopes "filters"
}
