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
	 * @brief get files from server.
	 * 
	 * @param $dir
	 * @param array $results
	 * @param array $content
	 * @return array
	 * @throws \Exception
	 */
	public static function getFiles($dir, &$results = array(), &$content = array())
	{

		if (!is_dir($dir))
			throw  new \Exception("directory is not available !");
		$files = scandir($dir);

		foreach($files as  $value){

			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);

			if(!is_dir($path)) {

				$results[] = $path;
				$ret_path =array_filter(explode('/',$path)); //remove empty elements
				$search_result = preg_grep("/^page-[0-9]+/",$ret_path); //search for page-[0-9] with regex

				if ($search_result == null) //null mean page-[0-9] word not used in path
					throw new \Exception("Error: lesson default files path syntax is not valid!\nyour syntax:$path\n(valid syntax: ../topic-1/page-2)");
				if (count($search_result)>1)
					throw new \Exception("Error: don't use 'page-[0-9]+' word in path More than once!");

				$page_word_index = array_keys($search_result); //return index of page-[0-9] word in array
				$ret_path = array_splice($ret_path,$page_word_index[0]); //return array from specified key to last
				$file = implode('/',$ret_path);//concat array elements to make needed path
				$value = file_get_contents($path);//get given path content
				$content[] = ["file" => $file, "content" => $value];



			} else if($value != "." && $value != "..") {
				self::getFiles($path, $results, $content);
			}
		}

		return $content;
	}


	/**
	 * @param $files
	 * @param $baseDir
	 * @return bool
	 * @throws \Exception
	 */
	public static function createFiles($files, $baseDir)
	{
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


	/**
	 * @brief recursive copy files from source to destination
	 * @param $src
	 * @param $dst
	 * @throws \Exception
	 */
	public static function recurse_copy($src,$dst)
	{
		if (!is_dir($src)) {
			throw new \Exception("Error : directory ($src) is not available !");
		}
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


	/**
	 * @brief delete files in path.
	 *
	 * @param $path
	 * @return bool
	 * @throws \Exception
	 */
	public  static function deleteFilesInDirectory($path)
	{
		
		if (!is_dir($path))
			throw  new \Exception("Error: $path is not valid directory !");

		try {

			$di = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
			$ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
			foreach ( $ri as $file ) {
			    $file->isDir() ?  rmdir($file) : unlink($file);
			}
			return true;
			
		}catch (\Exception $e){

			throw new \Exception("Couldn't remove user Code!".$e->getMessage());
		}

	}
}


