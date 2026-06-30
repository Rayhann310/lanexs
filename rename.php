<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
foreach ($dir as $file) {
    if ($file->isFile() && !strpos($file->getPathname(), '.git') && !strpos($file->getPathname(), 'vendor')) {
        $ext = $file->getExtension();
        if (in_array($ext, ['php', 'js', 'html', 'json'])) {
            $content = file_get_contents($file->getPathname());
            $newContent = str_replace(['LANEXS', 'Lanexs', 'LANEXSS Logistics'], ['LANEXSS', 'Lanexss', 'LANEXSS Logistics'], $content);
            if ($content !== $newContent) {
                file_put_contents($file->getPathname(), $newContent);
                echo "Updated: " . $file->getPathname() . "\n";
            }
        }
    }
}
echo "Done.\n";
