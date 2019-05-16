<?php
namespace App\Plugins\Storage\Controllers;

use App\Plugins\Storage\Forms\DirectoryForm;
use Pandora3\Core\Controller\Controller;
use Pandora3\Core\Interfaces\ResponseInterface;

class StorageController extends Controller {
	public function getRoutes(): array {
		return [
			'/' => 'files',
			/*'/add' => 'add',
			'/update' => 'update',
			'/delete' => 'delete',*/
			'/directory' => 'directory',
			'/directory/add' => 'directoryAdd',
			'/download' => 'download'
		];
	}

	protected function files(): ResponseInterface {
		$items = [
			['isDirectory' => true, 'id' => 1, 'title' => 'dir1', 'link' => '/storage?directory=1'],
			['isDirectory' => true, 'id' => 3, 'title' => 'dir2', 'link' => '/storage?directory=3'],
			['id' => 1, 'title' => 'file2', 'link' => '/storage/download/1'],
			['id' => 2, 'title' => 'file23', 'link' => '/storage/download/2'],
		];

		return $this->render('Files', compact('items'));
	}

	protected function directory():ResponseInterface {
		$directoryId = $this->request->get('directory');

		dump($directoryId);
		exit();

		$files = [];

		return $this->render('Directory', compact('files'));
	}

	protected function directoryAdd() {

		$form = new DirectoryForm($this->request);
		return $this->render('DirectoryForm', compact('form'));
	}

	protected function download():ResponseInterface {

		$filename = $this->request->get('');

		dump('1212');
	}
}