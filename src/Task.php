<?php

class Task {
    public $id;
    public $title;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($id, $title, $description, $status, $created_at, $updated_at) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
?>
