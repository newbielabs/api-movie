<?php
namespace Web;

defined('APP') OR exit('No direct script access allowed');

/**
 * Class Cinema
 * 		For adding new sources, please follow the format from this sample class.
 * 		Feel free to contribute to make it better.
 *
 * @link https://en.indoxxi.net/ Website for scrapping sources
 * @author Asep Fajar Nugraha <delve_brain@hotmail.com>
 * @version 1.0
 */

// Urls
define('URL_BASE', 'https://en.indoxxi.net//');

// For default List URL
define('URL_LIST_DEFAULT', 'https://en.indoxxi.net//21cineplex/');

// For featured List URL
define('URL_LIST_FEATURED', 'https://en.indoxxi.net//21cineplex-unggulan/');

// For now playing List URL
define('URL_LIST_NOWPLAYING', 'https://en.indoxxi.net//21cineplex-now-playing/');

// For coming soon List URL
define('URL_LIST_COMINGSOON', 'https://en.indoxxi.net//21cineplex-coming-soon/');

// For detail URL
define('URL_DETAIL', 'https://en.indoxxi.net//movie/');

class Cinema
{
	// Arguments
	private $args = array();

	/**
	 * Constructor
	 *
	 * @param array $args Parameter passing on instance
	 */
	public function __construct($args = array())
	{
		$this->args = $args;
	}

	public function getList()
	{
		if ( ! empty($this->args['type']) &&  ! empty($this->args['page']))
		{
			switch ($this->args['type']) {
	            case 'featured':
	                $url = URL_LIST_FEATURED;
	            break;
	            case 'now-playing':
	                $url = URL_LIST_NOWPLAYING;
	            break;
	            case 'coming-soon':
	                $url = URL_LIST_COMINGSOON;
	            break;
	            default:
	                $url = URL_LIST_DEFAULT;
	            break;
	        }

			// Element
	        $elem['url']  = $url . $this->args['page'];
	        $elem['list']       = 'div[class="ml-item"] a';
	        $elem['rating']     = 'span[class="mli-rating"]';
	        $elem['duration']   = 'span[class="mli-durasi"]';
	        $elem['quality']    = 'span[class="mli-quality"]';
	        $elem['paging']     = 'ul[class="pagination"] li';
	        $elem['total_page'] = 'data-ci-pagination-page';

	        // Scrapping
	        require('lib/dom/htmlDom.php');
	        $html = file_get_html($elem['url']);

	        $data = array();

	        if ($html->find($elem['list']) !== null)
	        {
	            foreach($html->find($elem['list']) as $key => $e)
	            {
	                $data[] = array(
	                    'id'        => str_replace('/online/', '', trim($e->getAttribute('href'))),
	                    'title'     => $e->getAttribute('title'),
	                    'image'     => ($e->find('img', 0) !== null
	                                    ? $e->find('img', 0)->getAttribute('data-original') : ''),
	                    'rating'    => trim(($e->find($elem['rating'], 0) !== null 
	                                    ? str_replace("<iclass='fafa-starmr5'></i>", '', trim(preg_replace('/\s+/','',$e->find($elem['rating'], 0)->innertext))) : '')),
	                    'duration'  => trim(($e->find($elem['duration'], 0) !== null
                                        ? str_replace("<iclass='fafa-clock-omr5'></i>", '', trim(preg_replace('/\s+/','',$e->find($elem['duration'], 0)->innertext))) : '')),
	                    'quality'   => trim(($e->find($elem['quality'], 0) !== null
	                                    ? trim($e->find($elem['quality'], 0)->innertext) : '')),
	                );
	            }
	        }

	        if ( ! empty($data))
	        {
	            $total_page = $html->find($elem['paging'], -1);
	            $total_page = ($total_page !== null 
	                ? ($total_page->find('a', 0) !== null 
	                    ? $total_page->find('a', 0)->getAttribute($elem['total_page']) : 0) : 0);
	            $total_page = (int) $total_page;

	            $result['code'] = 200;
	            $result['data'] = $data;
	            $result['summary'] = array(
	                    'total_data' => count($data),
	                    'total_page' => $total_page,
	                );
	        }
	        else
	        {
	            $result['code']     = 404;
	            $result['message']  = 'Not Found';
	        }
	    }
        else
        {
            $result['code']     = 400;
            $result['message']  = 'Bad Request';
        }

        return $result;
	}

	public function getDetail()
    {
        if ( ! empty($this->args['id']))
        {
            // Element
            $elem['url'] = URL_DETAIL . $this->args['id'];
            $elem['title']      = 'div[class="mvic-desc"] h3[itemprop="name"]';
            $elem['image']      = 'div[class="thumb mvic-thumb"] meta[itemprop="image"]';
            $elem['rating']     = 'span[itemprop="ratingValue"]';
            $elem['vote']       = 'span[itemprop="ratingCount"]';
            $elem['duration']   = 'div[class="mvici-right"] p';
            $elem['quality']    = 'span[class="quality"]';

            $elem['genre']      = 'div[class="mvici-left"] p';
            $elem['cast']       = 'div[class="mvici-left"] p';
            $elem['director']   = 'div[class="mvici-left"] p';
            $elem['production'] = 'div[class="mvici-right"] p';
            $elem['release']    = 'div[class="mvici-right"] meta[itemprop="datePublished"]';
            $elem['synopsis']['id'] = 'div[class="sinopsis-indo"]';
            $elem['synopsis']['en'] = 'div[class="desc"]';

            // Scrapping
            require('lib/dom/htmlDom.php');
            $html = file_get_html($elem['url']);

            $data['id']         = $this->args['id'];
            $data['title']      = trim(($html->find($elem['title'], 0) !== null 
                                    ? $html->find($elem['title'], 0)->getAttribute('content') : ''));

            if ( ! empty($data['title']))
            {
                $data['image']      = trim(($html->find($elem['image'], 0) !== null 
                                        ? $html->find($elem['image'], 0)->getAttribute('content') : ''));
                $data['rating']     = trim(($html->find($elem['rating'], 0) !== null 
                                        ? $html->find($elem['rating'], 0)->getAttribute('content') : ''));
                $data['vote']       = trim(($html->find($elem['vote'], 0) !== null 
                                        ? $html->find($elem['vote'], 0)->getAttribute('content') : ''));
                $data['duration']   = trim(($html->find($elem['duration'], 0) !== null
                                        ? trim(str_replace('<strong>Durasi Film:</strong>', '', trim($html->find($elem['duration'], 0)->innertext))) : ''));
                $data['duration']   = ( ! empty($data['duration']) ? trim(str_replace('Menit', '', $data['duration'])) : '');
                $data['quality']    = trim(($html->find($elem['quality'], 0) !== null
                                        ? trim($html->find($elem['quality'], 0)->innertext) : ''));

                // Genre
                $get_genre = ($html->find($elem['genre'], 0) !== null
                            ? trim(strip_tags($html->find($elem['genre'], 0)->innertext))
                            : '');
                $genre = array();
                if ( ! empty($get_genre))
                {
                    $get_genre = trim(str_replace('Genre:', '', $get_genre));
                    $get_genre = explode(',', $get_genre); 
                    foreach ($get_genre as $key => $value)
                    {
                       if ( ! empty(trim($value))) $genre[] = trim($value);
                    }
                }

                // Cast
                $get_cast = ($html->find($elem['cast'], 1) !== null
                            ? trim(strip_tags($html->find($elem['cast'], 1)->innertext))
                            : '');
                $cast = array();
                if ( ! empty($get_cast))
                {
                    $get_cast = trim(str_replace('Aktris:', '', $get_cast));
                    $get_cast = explode(',', $get_cast); 
                    foreach ($get_cast as $key => $value)
                    {
                       if ( ! empty(trim($value))) $cast[] = trim($value);
                    }
                }

                // Director
                $get_director = ($html->find($elem['director'], 2) !== null
                            ? trim(strip_tags($html->find($elem['director'], 2)->innertext))
                            : '');
                $director = array();
                if ( ! empty($get_director))
                {
                    $get_director = trim(str_replace('Sutradara:', '', $get_director));
                    $get_director = explode(',', $get_director); 
                    foreach ($get_director as $key => $value)
                    {
                       if ( ! empty(trim($value))) $director[] = trim($value);
                    }
                }

                // Production
                $get_production = ($html->find($elem['production'], 3) !== null
                            ? trim(strip_tags($html->find($elem['production'], 3)->innertext))
                            : '');
                $production = array();
                if ( ! empty($get_production))
                {
                    $get_production = trim(str_replace('Produksi:', '', $get_production));
                    $get_production = explode(',', $get_production); 
                    foreach ($get_production as $key => $value)
                    {
                       if ( ! empty(trim($value))) $production[] = trim($value);
                    }
                }

                // Release
                $release = (($html->find($elem['release'], 0) !== null)
                            ? trim(strip_tags($html->find($elem['release'], 0)->getAttribute('content')))
                            : '');

                $data['meta']['genre']      = $genre;
                $data['meta']['cast']       = $cast;
                $data['meta']['director']   = $director;
                $data['meta']['production'] = $production;
                $data['meta']['release']    = $release;

                $data['synopsis']['id'] = ($html->find($elem['synopsis']['id'], 0) !== null
                                            ? trim(strip_tags($html->find($elem['synopsis']['id'], 0)->innertext))
                                            : '');
                $data['synopsis']['en'] = ($html->find($elem['synopsis']['en'], 0) !== null
                                            ? trim(strip_tags($html->find($elem['synopsis']['en'], 0)->innertext)) 
                                            : '');

                $trailer = $html->find('script[!src], script[!type]', -1)->innertext;
                $trailer = substr($trailer, strpos($trailer, 'https://www.youtube.com'));
                $trailer = explode("?", $trailer);
                $trailer = (isset($trailer[0]) ? $trailer[0] : '');

                $data['movie']['trailer']       = $trailer;
                $data['movie']['playback_url']  = URL_DETAIL . $this->args['id'] . '/play';

                $result['code'] = 200;
                $result['data'] = $data;
            }
            else
            {
                $result['code']     = 404;
                $result['message']  = 'Not Found';
            }
        }
        else
        {
            $result['code']     = 400;
            $result['message']  = 'Bad Request';
        }

        return $result;
    }
}
