<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class TourOperator extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        "company_id",
        "is_parent",
        "name",
        "ref_id",
        "has_gfp_requests",

        "active",

        "delegate_name",
        "delegate_email",
        "delegate_mobile_number",
        "delegate_user_id",
        "assigned_user_id",

        "region_id",
        "country_id",
        "city_id",
        "area_id",
        "street",
        "postal_code",
        "phone",
        "fax",
        "email",
        "website",

        "is_company_address",
        
        "created_by",
        "updated_by",
        "deleted_by"
    ];

    public function get_pagination($filter)
    {
        $requestPagination = request()->input('pagination');
        $pagination = ($requestPagination && is_numeric($requestPagination)) ? $requestPagination : 10;

        $searchQuery = request()->input('search');

        return $this->where($filter)->where(function ($query) use ($searchQuery){

            $columns = Schema::getColumnListing('tour_operators');
            foreach($columns as $column)
            {
                $query->orWhere($column, 'LIKE', '%' . $searchQuery . '%');
            }
            
        })->paginate($pagination);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'tour_operator_city', 'tour_operator_id', 'city_id');
    }

    public function travelagencies()
    {
        return $this->belongsToMany(TravelAgency::class, 'travel_agency_tour_operator', 'tour_operator_id', 'travel_agency_id');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function imagesFullData()
    {
        return $this->images()->select('id', DB::raw("CONCAT('".asset('images/tour_operators')."', '/', file_name) AS file_name"));
    }     

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function assignuser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function delegateuser()
    {
        return $this->belongsTo(User::class, 'delegate_user_id');
    }

    public function createdbyuser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function teeTimes()
    {
        $teeTimes = RequestProductTeeTime::join('request_products', 'request_products.id', 'request_product_tee_times.request_product_id')
                                        ->join('request_destinations', 'request_products.request_destination_id', 'request_destinations.id')
                                        ->join('requests', 'requests.id', 'request_destinations.request_id')
                                        ->where('requests.tour_operator_id', $this->id)
                                        ->get();    
        return $teeTimes;
    }

    public function get_teeTimes()
    {
        $teeTimes = RequestProductTeeTime::whereHas('requestProduct.destination.request', function ($q) {
                    $q->where('tour_operator_id', $this->id);
            })->get();
        return $teeTimes;
    }

    public function translations()
    {
        return $this->morphMany(BasicTranslation::class, 'basicable');
    }
}
