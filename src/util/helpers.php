<?php


use GeniusTS\HijriDate\Hijri;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

if (!function_exists('_fake_file')) {
    /**
     * @param string $path
     * @param bool $stored
     * @param bool $image
     * @param string $format
     * @return false|\Illuminate\Http\Testing\File|string
     */
    function _fake_file(string $path = 'image', bool $stored = false, bool $image = true, $format = 'png')
    {
        $imageOrFileMethod = $image ? 'image' : 'create';

        return $stored ?
            UploadedFile::fake()->{$imageOrFileMethod}("$path.$format")->storePublicly("{$path}s", 'public') :
            UploadedFile::fake()->{$imageOrFileMethod}("$path.$format");
    }
}

if (!function_exists('_success_msg')) {
    /**
     * @param string $resourceName
     * @param string $operation
     * @return string[]
     */
    function _success_msg(string $resourceName, string $operation)
    {
        return ['status' => " تم {$operation} {$resourceName} بنجاح "];
    }
}

if (!function_exists('_file_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function _file_path(?string $path)
    {
        return $path ? asset("storage/$path") : null;
    }
}

if (!function_exists('_delete_file')) {
    /**
     * @param string|null $path
     * @throws FileNotFoundException
     */
    function _delete_file(?string $path)
    {
        if (!Storage::disk('public')->delete($path)) {
            throw new FileNotFoundException($path);
        }
    }
}

if (!function_exists('_assert_file')) {
    function _assert_file(UploadedFile $file, string $folderName = null, bool $public = true)
    {
        $public = $public ? 'public/' : '';
        $fileName = explode('.', $file->getClientOriginalName())[0];
        $folderName ??= Str::plural($fileName) . '/';

        Storage::assertExists("{$public}{$folderName}{$file->hashName()}");
    }
}

if (!function_exists('_gregorian')) {
    /**
     *
     * @param string $date
     * @return string
     */
    function _gregorian(string $date)
    {
        if (!$date) return null;

        $array = explode('/', $date);
        $dateAsArray = [
            'year' => $array[0],
            'month' => $array[1],
            'day' => $array[2],
        ];
        return Hijri::convertToGregorian(
            $dateAsArray['day'],
            $dateAsArray['month'],
            $dateAsArray['year']
        )->toDateTimeString();
    }
}

if (!function_exists('_hijri')) {
    /**
     * @param $date
     * @return string
     */
    function _hijri($date)
    {
        return Hijri::convertToHijri($date)->format('Y-m-d');
    }
}
