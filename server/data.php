<?php

class Data implements JsonSerializable {
    public $tableName = "";
    public $tableData = [];

    public function jsonSerialize(): mixed {
        return [
            $this->tableName => $this->tableData
        ];
    }
}

?>