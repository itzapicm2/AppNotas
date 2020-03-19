<?php

class Modelo_notas extends CI_Model{
    private $tabla = 'nota';

    public function create_note() {

    }

    public function read() {
        $query = $this->db->get($this->tabla);
        return $query->result();
    }


    public function borrar($id) {
        $this->db->where('id', $id)->delete($this->tabla);
    }

    public function add_update_nota($datos)
    {
        $this->db->replace($this->tabla, $datos);
    }
}

