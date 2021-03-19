<?php


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;

if (! function_exists('fake_file')) {
    /**
     * @param string $path
     * @param bool $stored
     * @return false|\Illuminate\Http\Testing\File|string
     */
    function x_fake_file(string $path = 'image', $stored = false)
    {
        return $stored ?
            UploadedFile::fake()->create("{$path}.png")->storePublicly("{$path}s", 'public') :
            UploadedFile::fake()->create("{$path}.png");
    }
}

if (! function_exists('success_msg')) {
    /**
     * @param string $resourceName
     * @param string $operation
     * @return string[]
     */
    function x_success_msg(string $resourceName, string $operation)
    {
        return ['status' => " تم {$operation} {$resourceName} بنجاح "];
    }
}

if (! function_exists('image_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function x_image_path(?string $path)
    {
        return $path ? asset("storage/$path") : null;
    }
}

if (! function_exists('delete_file')) {
    /**
     * @param string|null $path
     * @throws FileNotFoundException
     */
    function x_delete_file(?string $path)
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new FileNotFoundException($path);
        }
    }
}
