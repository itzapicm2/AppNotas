<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notes extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->view('entries');
    }
    
    public function read()
	{
        header('Content-type:application/json;charset=utf-8');

        $this->load->model('modelo_notas');
        echo(json_encode($this->modelo_notas->read()));
    }
    public function destroy()
    {
        $id = $this->input->get('id');
        if ($id) {
            $this->load->model('modelo_notas');
            $this->modelo_notas->borrar($id);
            $this->read();

        }
    }

    public function write() {
        $id = $this->input->post('id');
        $titulo = $this->input->post('titulo');
        $contenido = $this->input->post('contenido');
        $campos = array();
        if ($id) {
            $campos['id'] = $id;
        }
        if ($titulo and $contenido) {
            $this->load->model('modelo_notas');
            $campos['titulo'] = $titulo;
            $campos['contenido'] = $contenido;
            $this->modelo_notas->add_update_nota($campos);
            http_response_code(201);
            $this->read();
        } else{
            echo('{"message":"Formato sin piernas xd"}');
        }
    }
}