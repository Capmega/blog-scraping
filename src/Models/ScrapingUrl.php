<?php

namespace Sdkconsultoria\BlogScraping\Models;

use Sdkconsultoria\Base\Models\ResourceModel;

class ScrapingUrl extends ResourceModel
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules($params)
    {
        return [
            'scraping_source_id' => 'required',
            'url'    => 'required',
            'driver' => 'required',
            'name'   => 'required',
        ];
    }

    /**
     * Get client attributes values.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        return array_merge($attributes, [
            'scraping_source_id' => __('scraping::attributes.url.scraping_source_id'),
            'url' => __('scraping::attributes.url.url'),
            'driver' => __('scraping::attributes.url.driver'),
        ]);
    }

    /**
     * Get attributes for search.
     *
     * @return array
     */
    public function getFiltersAttribute()
    {
        $attributes = parent::getFiltersAttribute();
        return array_merge([
        ], $attributes);
    }

    /**
     * Save item
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public function save(array $options = [])
    {
        $this->generateSeoname();
        parent::save($options);
    }
}
