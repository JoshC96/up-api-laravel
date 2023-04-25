<?php 

namespace App\Http\UpApi;

class Util 
{
    /**
     * Extracts the 'next' and 'prev' keys from the UpApi "Links" URLs. 
     * The Up Banking API uses opaque cursors for pagination. 
     * Clients must refer to the prev and next keys to move between pages.
     * 
     * @param array $links 
     * @return array 
     */
    public static function getPaginationKeys(array $links): array {
        $params = [];

        foreach ($links as $key => $link) {
            $query = parse_url($link, PHP_URL_QUERY);
            parse_str($query, $query_params);

            $params['next'] = $query_params['page']['after'] ?? null;
            $params['prev'] = $query_params['page']['before'] ?? null;
        }

        return $params;
    }


}