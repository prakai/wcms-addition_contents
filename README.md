# wcms-addition_contents
This is a plugin for WonderCMS (https://www.wondercms.com). It allows to add and manage additional contents on page.

# Download and Setup
1. Download the ZIP file.
2. Create folder named "addition_contents" in WonderCMS plugins folder. 
3. Unzip all folders and files from zip file in to created folder.
4. The new toolbar will show below a default content box of page.

# For WonderCMS-2.0
Add hook to wCMS::page() method by replace the last line of wCMS::page().

public static function page($key) {

	:
	
-	return isset($keys[$key]) ? $keys[$key] : '';
+	$content = isset($keys[$key]) ? $keys[$key] : '';
+	return wCMS::_hook('onPage', $content, $key)[0];
}  

# Features
- Add, delete multiple additional contents below default content box.
- Additional contents is separate on each page.
- Allow to show or hide each additional contents.
- Suports both WonderCMS-1.x and WonderCMS-2.0

# Update
* 1.1.0 - 2017-02-27
 - Adds support both WonderCMS-1.x and WonderCMS-2.0
 - Bug fix
* 1.0.0 - 2017-02-21
 - Initial version
