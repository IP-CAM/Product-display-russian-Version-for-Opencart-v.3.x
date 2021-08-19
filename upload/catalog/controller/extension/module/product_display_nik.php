<?php
class ControllerExtensionModuleProductDisplayNik extends Controller {
	public function index($setting) {
	    static $module = 0;
		$this->load->language('extension/module/product_display_nik');

		$this->load->model('catalog/product');
		$this->load->model('extension/module/product_display_nik');

		$this->load->model('tool/image');

        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');

		$data['random_products'] = array();

		$data = $setting;

        if ($setting['display_ordered'] && $this->customer->isLogged()) {
            $ordered_products = $this->model_extension_module_product_display_nik->getOrderedProductsByCustomerId($this->customer->isLogged(), $setting['ordered_count_products']);
            if ($setting['display_type'] == 'carousel' || $setting['display_type'] == 'table' || $setting['display_type'] == 'list') {
                if ($setting['display_format']) {
                    $data['ordered_products'] = $this->productsProcessing($ordered_products);
                } else {
                    $data['random_products'] = $this->mergeProducts(isset($data['random_products']) ? $data['random_products'] : array(), $this->productsProcessing($ordered_products));
                }
            } else {
                $data['ordered_products'] = $this->productsProcessing($ordered_products);
            }
        }

        if($setting['display_viewed']) {
            if (isset($_COOKIE['viewed_products'])) {
                $viewed_products = array_slice(explode(',', $_COOKIE['viewed_products']), 0, (int)$setting['viewed_count_products']);
                if ($setting['display_type'] == 'carousel' || $setting['display_type'] == 'table' || $setting['display_type'] == 'list') {
                    if ($setting['display_format']) {
                        $data['viewed_products'] = $this->productsProcessing($viewed_products);
                    } else {
                        $data['random_products'] = $this->mergeProducts(isset($data['random_products']) ? $data['random_products'] : array(), $this->productsProcessing($viewed_products));
                    }
                } else {
                    $data['viewed_products'] = $this->productsProcessing($viewed_products);
                }
            }

        }

        if($setting['display_favorite']) {
            if (isset($_COOKIE['wishlist'])) {
                $favorite_products = array_slice(explode(',', $_COOKIE['wishlist']), 0, (int)$setting['favorite_count_products']);
                if ($setting['display_type'] == 'carousel' || $setting['display_type'] == 'table' || $setting['display_type'] == 'list') {
                    if ($setting['display_format']) {
                        $data['favorite_products'] = $this->productsProcessing($favorite_products);
                    } else {
                        $data['random_products'] = $this->mergeProducts(isset($data['random_products']) ? $data['random_products'] : array(), $this->productsProcessing($favorite_products));
                    }
                } else {
                    $data['favorite_products'] = $this->productsProcessing($favorite_products);
                }
            }

        }

        if($setting['display_bestsellers']) {
            $bestsellers_products = $this->model_catalog_product->getBestSellerProducts($setting['bestsellers_count_products']);
            if ($setting['display_type'] == 'carousel' || $setting['display_type'] == 'table' || $setting['display_type'] == 'list') {
                if ($setting['display_format']) {
                    $data['bestsellers_products'] = $this->productsProcessing($bestsellers_products);
                } else {
                    $data['random_products'] = $this->mergeProducts(isset($data['random_products']) ? $data['random_products'] : array(), $this->productsProcessing($bestsellers_products));
                }
            } else {
                $data['bestsellers_products'] = $this->productsProcessing($bestsellers_products);
            }
        }

        if($setting['display_recommendation']) {
            if (!empty($setting['product'])) {
                $recommended_products = array_slice($setting['product'], 0, (int)$setting['recommendation_count_products']);
                if ($setting['display_type'] == 'carousel' || $setting['display_type'] == 'table' || $setting['display_type'] == 'list') {
                    if ($setting['display_format']) {
                        $data['recommended_products'] = $this->productsProcessing($recommended_products);
                    } else {
                        $data['random_products'] = $this->mergeProducts(isset($data['random_products']) ? $data['random_products'] : array(), $this->productsProcessing($recommended_products));
                    }
                } else {
                    $data['recommended_products'] = $this->productsProcessing($recommended_products);
                }
            }
        }

        if (!empty($data['random_products'])) {
            shuffle($data['random_products']);
        }

		$data['module'] = $module++;

        return $this->load->view('extension/module/product_display_nik', $data);
	}

	private function mergeProducts($products, $needle) {
	    if (empty($products)) {
	        $products = array();
        }

	    $products_ids = array();

	    if (!empty($needle)) {
            foreach ($products as $product) {
                $products_ids[] = $product['product_id'];
            }

	        foreach ($needle as $item) {
	            if (!in_array($item['product_id'], $products_ids)) {
                    array_push($products, $item);
                }
            }
        }

	    return $products;
    }

	private function productsProcessing($products) {
	    $results = array();

        foreach ($products as $product_id) {
            if (!is_array($product_id)) {
                $product_info = $this->model_catalog_product->getProduct($product_id);
            } else {
                $product_info = $product_id;
            }

            if ($product_info) {
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'],  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png',  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $product_info['rating'];
                } else {
                    $rating = false;
                }

                $this->load->model('catalog/category');
                $this->load->model('catalog/product');
                $getCategories = $this->model_catalog_product->getCategories($product_info['product_id']);
                $path = '';

                $categoriesPaths = array();
                $max_count = 0;
                foreach ($getCategories as $getCategory) {
                    $categoriesPaths[] = $this->model_catalog_category->getCategoryPathHighestLevel($getCategory['category_id']);
                }
                foreach ($categoriesPaths as $categoriesPath) {
                    if ($max_count < count($categoriesPath)) {
                        $max_count = count($categoriesPath);
                    }
                }
                foreach ($categoriesPaths as $key => $categoriesPath) {
                    if ($max_count > count($categoriesPath)) {
                        unset($categoriesPaths[$key]);
                    }
                }
                if (!empty($categoriesPaths)) {
                    $min_category_id = 1000000000;
                    $currentCategoryPaths = array();

                    foreach ($categoriesPaths as $key => $item) {
                        if (isset($item[0]) && isset($item[0]['path_id']) && $item[0]['path_id'] < $min_category_id) {
                            $min_category_id = $item[0]['path_id'];
                            $currentCategoryPaths = $item;
                        }
                    }

                    foreach ($currentCategoryPaths as $kk => $currentCategoryPath) {
                        if ($kk != (count($currentCategoryPaths) - 1)) {
                            $path .= $currentCategoryPath['path_id'] . '_';
                        } else {
                            $path .= $currentCategoryPath['path_id'];
                        }
                    }
                }

                if(!empty($path)) {
                    $product_link = $this->url->link('product/product', 'path=' . $path . '&product_id=' . $product_info['product_id']);
                } else {
                    $product_link = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);
                }

                $results[] = array(
                    'product_id'  => $product_info['product_id'],
                    'thumb'       => $image,
                    'name'        => $product_info['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $product_link
                );
            }
        }

        return $results;
    }
}