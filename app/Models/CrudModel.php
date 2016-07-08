<?php

namespace App\Models;

class CrudModel
{
	public function getAllNotes()
	{
		return $this->pdo->from('notes')->where('soft_delete', null)->orderBy('created_at DESC');
	}

	public function getSingleNote($id)
	{
		return $this->pdo->from('notes')->where('note_id', $id)->fetch();
	}
}