<?php
    class Database {
      public $db;

      public function __construct($db) {
        $this->db = $db;
      }

      public function get_last_primary_key($column_name, $table_name) {
        $result = $this->db->prepare("SELECT MAX($column_name) FROM $table_name");
        $result->execute();
        $max_value = $result->fetchColumn();
        return $max_value ? $max_value : 0;
      }

      public function insert_into_table($table_name, $column_names, $values) {
        $columns = implode(', ', $column_names);
        $placeholders = ':' . implode(', :', $column_names);
        
        $sql = "INSERT INTO $table_name ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        foreach ($column_names as $i => $column_name) {
            $stmt->bindValue(":$column_name", $values[$i]);
        }

        $stmt->execute();
      }

      public function check_user_existence($column_name, $table_name, $search_val) {
        $stmt = $this->db->prepare("SELECT $column_name FROM $table_name");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as $array) {
          if (array_search($search_val, $array)) {
            return true;
          }
        }
        
        return false;
      }

      public function update_table($table_name, $values, $condition) {
        $set_parts = [];
        foreach ($values as $column => $value) {
            $set_parts[] = "$column = :$column";
        }

        $set_clause = implode(", ", $set_parts);
        $sql = "UPDATE $table_name SET $set_clause WHERE $condition";
        $stmt = $this->db->prepare($sql);

        foreach ($values as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }

        return $stmt->execute();
    }

    function get_info($column_name, $table, $condition, $params, $all=false) {
      $pdo = $this->db;
      $sql = "SELECT $column_name FROM $table WHERE $condition";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);

      return $all ? $stmt->fetchAll() : $stmt->fetchColumn();
    }
  }
?>