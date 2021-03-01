<?php
class ModelExtensionModuleProductDisplayNik extends Model {
    public function getOrderedProductsByCustomerId($customer_id, $limit) {

        $query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.customer_id = '" . (int)$customer_id . "' LIMIT " . $limit);

        $results = array();

        foreach ($query->rows as $row) {
            $results[] = $row['product_id'];
        }

        return $results;

    }
}