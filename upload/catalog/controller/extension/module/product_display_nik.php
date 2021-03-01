<?php
class ControllerExtensionModuleProductDisplayNik extends Controller {
	public function index($setting) {
	    static $module = 0;
		$this->load->language('extension/module/product_display_nik');

		$this->load->model('catalog/product');
		$this->load->model('extension/module/product_display_nik');

		$this->load->model('tool/image');

		$data['products'] = array();

//		var_dump($setting);

		$data = $setting;

        if ($setting['display_ordered'] && $this->customer->isLogged()) {
            if ($setting['display_format']) {
                $ordered_products = $this->model_extension_module_product_display_nik->getOrderedProductsByCustomerId($this->customer->isLogged(), $setting['ordered_count_products']);
                $data['ordered_products'] = $this->productsProcessing($ordered_products);
            }
        }

        if($setting['display_viewed']) {
            if ($setting['display_format']) {
                if (isset($_COOKIE['viewed_products'])) {
                    $viewed_products = array_slice(explode(',', $_COOKIE['viewed_products']), 0, (int)$setting['viewed_count_products']);
                    $data['viewed_products'] = $this->productsProcessing($viewed_products);
                }
            }
        }

        if($setting['display_favorite']) {
            if ($setting['display_format']) {
                if (isset($_COOKIE['wishlist'])) {
                    $favorite_products = array_slice(explode(',', $_COOKIE['wishlist']), 0, (int)$setting['favorite_count_products']);
                    $data['favorite_products'] = $this->productsProcessing($favorite_products);
                }
            }
        }

        if($setting['display_bestsellers']) {
            if ($setting['display_format']) {
                $bestsellers_products = $this->model_catalog_product->getBestSellerProducts($setting['bestsellers_count_products']);
                $data['bestsellers_products'] = $this->productsProcessing($bestsellers_products);
            }
        }

        if($setting['display_recommendation']) {
            if ($setting['display_format']) {
                if (!empty($setting['product'])) {
                    $recommended_products = array_slice($setting['product'], 0, (int)$setting['recommendation_count_products']);
                    $data['recommended_products'] = $this->productsProcessing($recommended_products);

                }
            }
        }

//		if ($setting['display_format']) {
//
//        } else {
//            if (!empty($setting['product'])) {
//                $products = array_slice($setting['product'], 0, (int)$setting['recommendation_count_products']);
//
//                foreach ($products as $product_id) {
//                    $product_info = $this->model_catalog_product->getProduct($product_id);
//
//                    if ($product_info) {
//                        if ($product_info['image']) {
//                            $image = $this->model_tool_image->resize($product_info['image'],  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
//                        } else {
//                            $image = $this->model_tool_image->resize('placeholder.png',  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
//                        }
//
//                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
//                            $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
//                        } else {
//                            $price = false;
//                        }
//
//                        if ((float)$product_info['special']) {
//                            $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
//                        } else {
//                            $special = false;
//                        }
//
//                        if ($this->config->get('config_tax')) {
//                            $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
//                        } else {
//                            $tax = false;
//                        }
//
//                        if ($this->config->get('config_review_status')) {
//                            $rating = $product_info['rating'];
//                        } else {
//                            $rating = false;
//                        }
//
//                        $data['products'][] = array(
//                            'product_id'  => $product_info['product_id'],
//                            'thumb'       => $image,
//                            'name'        => $product_info['name'],
//                            'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
//                            'price'       => $price,
//                            'special'     => $special,
//                            'tax'         => $tax,
//                            'rating'      => $rating,
//                            'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
//                        );
//                    }
//                }
//            }
//        }

		$data['module'] = $module++;

//		if ($data['products']) {
			return $this->load->view('extension/module/product_display_nik', $data);
//		}
	}

	private function productsProcessing($products) {
	    $results = array();

        foreach ($products as $product_id) {
            if (count($product_id) < 2) {
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

                $results[] = array(
                    'product_id'  => $product_info['product_id'],
                    'thumb'       => $image,
                    'name'        => $product_info['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                );
            }
        }

        return $results;
    }
}