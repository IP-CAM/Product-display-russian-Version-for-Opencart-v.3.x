<?php
class ControllerExtensionModuleProductDisplayNik extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/product_display_nik');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('product_display_nik', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['ordered_count_products'])) {
			$data['error_ordered_count_products'] = $this->error['ordered_count_products'];
		} else {
			$data['error_ordered_count_products'] = '';
		}

		if (isset($this->error['viewed_count_products'])) {
			$data['error_viewed_count_products'] = $this->error['viewed_count_products'];
		} else {
			$data['error_viewed_count_products'] = '';
		}

		if (isset($this->error['favorite_count_products'])) {
			$data['error_favorite_count_products'] = $this->error['favorite_count_products'];
		} else {
			$data['error_favorite_count_products'] = '';
		}

		if (isset($this->error['bestsellers_count_products'])) {
			$data['error_bestsellers_count_products'] = $this->error['bestsellers_count_products'];
		} else {
			$data['error_bestsellers_count_products'] = '';
		}

		if (isset($this->error['recommendation_count_products'])) {
			$data['error_recommendation_count_products'] = $this->error['recommendation_count_products'];
		} else {
			$data['error_recommendation_count_products'] = '';
		}

        if (isset($this->error['button_class'])) {
            $data['error_button_class'] = $this->error['button_class'];
        } else {
            $data['error_button_class'] = '';
        }

//        if (isset($this->error['cookie_lifetime_favorite'])) {
//            $data['error_cookie_lifetime_favorite'] = $this->error['cookie_lifetime_favorite'];
//        } else {
//            $data['error_cookie_lifetime_favorite'] = '';
//        }
//
//        if (isset($this->error['cookie_lifetime_viewed'])) {
//            $data['error_cookie_lifetime_viewed'] = $this->error['cookie_lifetime_viewed'];
//        } else {
//            $data['error_cookie_lifetime_viewed'] = '';
//        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/product_display_nik', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/product_display_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/product_display_nik', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/product_display_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['display_heading'])) {
			$data['display_heading'] = $this->request->post['display_heading'];
		} elseif (!empty($module_info)) {
			$data['display_heading'] = $module_info['display_heading'];
		} else {
			$data['display_heading'] = '';
		}

		if (isset($this->request->post['display_ordered'])) {
			$data['display_ordered'] = $this->request->post['display_ordered'];
		} elseif (!empty($module_info)) {
			$data['display_ordered'] = $module_info['display_ordered'];
		} else {
			$data['display_ordered'] = '';
		}

		if (isset($this->request->post['ordered_count_products'])) {
			$data['ordered_count_products'] = $this->request->post['ordered_count_products'];
		} elseif (!empty($module_info)) {
			$data['ordered_count_products'] = $module_info['ordered_count_products'];
		} else {
			$data['ordered_count_products'] = '';
		}

		if (isset($this->request->post['display_viewed'])) {
			$data['display_viewed'] = $this->request->post['display_viewed'];
		} elseif (!empty($module_info)) {
			$data['display_viewed'] = $module_info['display_viewed'];
		} else {
			$data['display_viewed'] = '';
		}

		if (isset($this->request->post['viewed_count_products'])) {
			$data['viewed_count_products'] = $this->request->post['viewed_count_products'];
		} elseif (!empty($module_info)) {
			$data['viewed_count_products'] = $module_info['viewed_count_products'];
		} else {
			$data['viewed_count_products'] = '';
		}

		if (isset($this->request->post['display_favorite'])) {
			$data['display_favorite'] = $this->request->post['display_favorite'];
		} elseif (!empty($module_info)) {
			$data['display_favorite'] = $module_info['display_favorite'];
		} else {
			$data['display_favorite'] = '';
		}

		if (isset($this->request->post['favorite_count_products'])) {
			$data['favorite_count_products'] = $this->request->post['favorite_count_products'];
		} elseif (!empty($module_info)) {
			$data['favorite_count_products'] = $module_info['favorite_count_products'];
		} else {
			$data['favorite_count_products'] = '';
		}

		if (isset($this->request->post['display_bestsellers'])) {
			$data['display_bestsellers'] = $this->request->post['display_bestsellers'];
		} elseif (!empty($module_info)) {
			$data['display_bestsellers'] = $module_info['display_bestsellers'];
		} else {
			$data['display_bestsellers'] = '';
		}

		if (isset($this->request->post['bestsellers_count_products'])) {
			$data['bestsellers_count_products'] = $this->request->post['bestsellers_count_products'];
		} elseif (!empty($module_info)) {
			$data['bestsellers_count_products'] = $module_info['bestsellers_count_products'];
		} else {
			$data['bestsellers_count_products'] = '';
		}

		if (isset($this->request->post['display_recommendation'])) {
			$data['display_recommendation'] = $this->request->post['display_recommendation'];
		} elseif (!empty($module_info)) {
			$data['display_recommendation'] = $module_info['display_recommendation'];
		} else {
			$data['display_recommendation'] = '';
		}

		if (isset($this->request->post['recommendation_count_products'])) {
			$data['recommendation_count_products'] = $this->request->post['recommendation_count_products'];
		} elseif (!empty($module_info)) {
			$data['recommendation_count_products'] = $module_info['recommendation_count_products'];
		} else {
			$data['recommendation_count_products'] = '';
		}

        $this->load->model('catalog/category');

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories();

        foreach ($categories as $category) {
            if ($category) {
                $data['categories'][] = array(
                    'category_id' => $category['category_id'],
                    'name'       => $category['name']
                );
            }
        }

        $this->load->model('catalog/product');

        $data['products'] = array();

        if (!empty($this->request->post['product'])) {
            $products = $this->request->post['product'];
        } elseif (!empty($module_info['product'])) {
            $products = $module_info['product'];
        } else {
            $products = array();
        }

        foreach ($products as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                $data['products'][] = array(
                    'product_id' => $product_info['product_id'],
                    'name'       => $product_info['name']
                );
            }
        }

		if (isset($this->request->post['display_type'])) {
			$data['display_type'] = $this->request->post['display_type'];
		} elseif (!empty($module_info)) {
			$data['display_type'] = $module_info['display_type'];
		} else {
			$data['display_type'] = 'carousel';
		}

		if (isset($this->request->post['display_format'])) {
			$data['display_format'] = $this->request->post['display_format'];
		} elseif (!empty($module_info)) {
			$data['display_format'] = $module_info['display_format'];
		} else {
			$data['display_format'] = '';
		}

		if (isset($this->request->post['button_class'])) {
			$data['button_class'] = $this->request->post['button_class'];
		} elseif (!empty($module_info)) {
			$data['button_class'] = $module_info['button_class'];
		} else {
			$data['button_class'] = '';
		}


//        if (isset($this->request->post['cookie_lifetime_favorite'])) {
//            $data['cookie_lifetime_favorite'] = $this->request->post['cookie_lifetime_favorite'];
//        } elseif (!empty($module_info)) {
//            $data['cookie_lifetime_favorite'] = $module_info['cookie_lifetime_favorite'];
//        } else {
//            $data['cookie_lifetime_favorite'] = '';
//        }
//
//        if (isset($this->request->post['cookie_lifetime_favorite_time_unit'])) {
//            $data['cookie_lifetime_favorite_time_unit'] = $this->request->post['cookie_lifetime_favorite_time_unit'];
//        } elseif (!empty($module_info)) {
//            $data['cookie_lifetime_favorite_time_unit'] = $module_info['cookie_lifetime_favorite_time_unit'];
//        } else {
//            $data['cookie_lifetime_favorite_time_unit'] = 'minute';
//        }
//
//        if (isset($this->request->post['cookie_lifetime_viewed'])) {
//            $data['cookie_lifetime_viewed'] = $this->request->post['cookie_lifetime_viewed'];
//        } elseif (!empty($module_info)) {
//            $data['cookie_lifetime_viewed'] = $module_info['cookie_lifetime_viewed'];
//        } else {
//            $data['cookie_lifetime_viewed'] = '';
//        }
//
//        if (isset($this->request->post['cookie_lifetime_viewed_time_unit'])) {
//            $data['cookie_lifetime_viewed_time_unit'] = $this->request->post['cookie_lifetime_viewed_time_unit'];
//        } elseif (!empty($module_info)) {
//            $data['cookie_lifetime_viewed_time_unit'] = $module_info['cookie_lifetime_viewed_time_unit'];
//        } else {
//            $data['cookie_lifetime_viewed_time_unit'] = 'minute';
//        }

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/product_display_nik', $data));
	}

	public function getProductsByCategory() {
        $json = array();

        if (isset($this->request->get['category_id'])) {
            $this->load->model('catalog/product');
            if ($this->request->get['category_id']) {
                // get by id
                $results = $this->model_catalog_product->getProductsByCategoryId($this->request->get['category_id']);
            } else {
                // get all
                $results = $this->model_catalog_product->getProducts();
            }

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => $result['name'],
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/product_display_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ($this->request->post['display_type'] == 'tabsInPopup') {
            if (!$this->request->post['button_class']) {
                $this->error['button_class'] = $this->language->get('error_button_class');
            }
        }

		if($this->request->post['display_ordered']) {
            if (!$this->request->post['ordered_count_products']) {
                $this->error['ordered_count_products'] = $this->language->get('error_count_products');
            }
        }

        if($this->request->post['display_viewed']) {
            if (!$this->request->post['viewed_count_products']) {
                $this->error['viewed_count_products'] = $this->language->get('error_count_products');
            }
        }

        if($this->request->post['display_favorite']) {
            if (!$this->request->post['favorite_count_products']) {
                $this->error['favorite_count_products'] = $this->language->get('error_count_products');
            }
        }

        if($this->request->post['display_bestsellers']) {
            if (!$this->request->post['bestsellers_count_products']) {
                $this->error['bestsellers_count_products'] = $this->language->get('error_count_products');
            }
        }

        if($this->request->post['display_recommendation']) {
            if (!$this->request->post['recommendation_count_products']) {
                $this->error['recommendation_count_products'] = $this->language->get('error_count_products');
            }
        }
//
//        if (!$this->request->post['cookie_lifetime_favorite']) {
//            $this->error['cookie_lifetime_favorite'] = $this->language->get('error_cookie_lifetime_favorite');
//        }
//
//        if (!$this->request->post['cookie_lifetime_viewed']) {
//            $this->error['cookie_lifetime_viewed'] = $this->language->get('error_cookie_lifetime_viewed');
//        }

		return !$this->error;
	}
}
