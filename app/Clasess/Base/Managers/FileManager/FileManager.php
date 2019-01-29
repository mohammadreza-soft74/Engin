<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:48 PM
 */

namespace App\Clasess\Base\Managers\FileManager;


class FileManager
{
	/**
	 * @brief get file from host
	 *
	 * @param $path
	 * @return array
	 * @throws \Exception
	 */
	public static function getFiles($path)
	{

		$content = [];

		if (!is_dir($path))
			throw  new \Exception("directory is not available !");

		if (!$dh = opendir($path))
			throw  new \Exception("opendir result must be Resource!");

		while ($file = readdir($dh)) {

			if ($file == ".." or $file == ".")
				continue;

			$input = file_get_contents($path . "/" . $file);

			if ($input == "")
				$input = " ";

			//if ($input == null)
			// continue;

			$content[] = ["file" => $file, "content" => $input];
		}

		return $content;
	}



	public static function createFiles($files, $baseDir)
	{

		// TODO: Use better validation idea
		// TODO: Support for files in folders

		// Check if it is an array
		if ( !is_array($files) )
			throw new \Exception("Files field must be an array.");

		// Check the count
		$count = count($files);
		if ( $count == 0 )
			throw new \Exception("Files field must contain at least one file.");

		// Create files
		foreach ( $files as $file )
		{

			// Validate
			if ( isset($file["name"]) == false )
				throw new \Exception("File name is not provided.");
			if ( isset($file["content"]) == false )
				throw new \Exception("File content is not provided.");

			// Initialize variables
			$name = $file["name"];
			$content = $file["content"];
			$path = realpath($baseDir) . DIRECTORY_SEPARATOR . $name;

			// Create file
			file_put_contents($path, $content);
			return true;
		}
	}


	public static function recurse_copy($src,$dst)
	{
		$dir = opendir($src);
		$oldmask = umask(0);
		@mkdir($dst,0777,true);
		umask($oldmask);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	public  static function deleteFilesInDirectory($path)
	{

		if (!is_dir($path))
			throw  new \Exception("directory is not available !");


		if (!$dh = opendir($path))
			throw  new \Exception("opendir result must be Resource!");
		try {

			while ($file = readdir($dh)) {
				@unlink($path . '/' . $file);
			}
		}catch (\Exception $e){

			throw new \Exception("Couldn't remove user Code!".$e->getMessage());
		}

	}
}


