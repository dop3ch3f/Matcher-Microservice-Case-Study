<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Http\Resources\SearchProfileResource;
use App\Models\Property;
use App\Models\SearchProfile;
use Illuminate\Http\Request;


class MatchController extends Controller
{
    public function computeMatchScore(Property $property, SearchProfile $searchProfile) {
        return 0;
    }

    public function getDeviatedNumber($num, $increment = false): int {
        if($increment) {
            return (int)((0.25 * $num) + $num);
        }
        return (int)((0.25 * $num) - $num);
    }

    public function computeLooseMatch($propertyValue, $searchProfileValue) : bool {
        // for direct field type
        if (!is_array($searchProfileValue)) {
            return false;
        }
        [$min, $max] = $searchProfileValue;

        $min = is_null($min) ? 'pass' : (int) $min;
        $max = is_null($max) ? 'pass' : (int) $max;

        // for range field type
        // check if propertyValue is within range of deviated
        return ($min === 'pass' || $propertyValue >= $this->getDeviatedNumber($min)) && ($max === 'pass' || $propertyValue <= $this->getDeviatedNumber((int)$max, true));
    }

    public function computeStrictMatch($propertyValue, $searchProfileValue): bool
    {
        // for direct field type
        if (!is_array($searchProfileValue)) {
            if($searchProfileValue === null) {
                return true;
            }
            return $propertyValue === $searchProfileValue;
        }
        // for range field type
        return array_key_exists($propertyValue, $searchProfileValue);
    }

    public function computeMatches(Property $property, SearchProfile $searchProfile) {
        $strict_match_count = 0;
        $loose_match_count = 0;

        $propertyFields = json_decode($property->fields, true);

        foreach (json_decode($searchProfile->search_fields, true) as $field => $value) {
                if(isset($propertyFields[$field])) {
                    if($this->computeStrictMatch($propertyFields[$field], $value)) {
                        $strict_match_count++;
                        continue;
                    }
                    if ($this->computeLooseMatch($propertyFields[$field], $value)) {
                        $loose_match_count++;
                    }
                }
        }

        return [
            'score' => (2 * $strict_match_count) + ($loose_match_count),
            'strictMatchesCount' => $strict_match_count,
            'looseMatchesCount' => $loose_match_count,
        ];
    }

    public function getPropertyMatches(Property $property) {

        $query = SearchProfile::query();

        foreach (json_decode($property->fields, true) as $key => $value ) {
            if (isset($value)) {
                if($key === 'returnActual') {
                    $query = $query->orWhereJsonContains('return_potential', [$value]);
                } else {
                    $query = $query->orWhereJsonContains('search_fields->'.$key, [$value]);
                }
            }
        }


        $result = array_map(function ($searchProfile) use ($property) {
            $result = $this->computeMatches($property, $searchProfile);
            return [
                'searchProfileId' => $searchProfile->id,
                'searchProfile' => new SearchProfileResource($searchProfile),
                'score' => $result['score'],
                'strictMatchesCount' => $result['strictMatchesCount'],
                'looseMatchesCount' => $result['looseMatchesCount'],
            ];
        }, $query->get()->all());

        usort($result, function ($a, $b) {
            if ($a['score'] == $b['score']) {
                return 0;
            }
            return ($a['score'] < $b['score']) ? 1 : -1;
        });

        return $result;
    }
}
