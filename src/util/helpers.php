<?php


use GeniusTS\HijriDate\Hijri;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

if (!function_exists('_fake_image')) {

    /**
     * @param string $path
     * @param bool $stored
     * @param string $format
     * @return false|\Illuminate\Http\Testing\File|string
     */
    function _fake_image(string $path = 'image', bool $stored = false, $format = 'png')
    {
        return $stored ?
            UploadedFile::fake()->image("$path.$format")->storePublicly("{$path}s", 'public') :
            UploadedFile::fake()->image("$path.$format");
    }
}

if (!function_exists('_fake_file')) {
    /**
     * @param string $path
     * @param bool $stored
     * @param string $format
     * @return false|\Illuminate\Http\Testing\File|string
     */
    function _fake_file(string $path = 'file', bool $stored = false, $format = 'pdf')
    {
        return $stored ?
            UploadedFile::fake()->create("$path.$format")->storePublicly("{$path}s", 'public') :
            UploadedFile::fake()->create("$path.$format");
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

if (!function_exists('_public_file_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function _public_file_path(?string $path)
    {
        return $path ? asset("storage/$path") : null;
    }
}

if (!function_exists('_delete_file')) {
    /**
     * @param string|null $path
     * @param string $disk
     */
    function _delete_file(string $path, string $disk = 'public')
    {
        if (!Storage::disk($disk)->delete($path)) {
            throw new FileNotFoundException($path);
        }
    }
}

if (!function_exists('_assert_file_exist')) {
    function _assert_file_exist(UploadedFile $file, string $folderName = null, bool $public = true)
    {
        $public = $public ? 'public/' : '';
        $fileName = explode('.', $file->getClientOriginalName())[0];
        $folderName ??= Str::plural($fileName) . '/';

        Storage::assertExists("{$public}{$folderName}{$file->hashName()}");
    }
}

if (!function_exists('_gregorian')) {
    /**
     * @param string $date
     * @return string
     * @example 1400/0/0
     */
    function _gregorian(string $date)
    {
        $array = explode('/', $date);

        if (count($array) !== 3) {
            throw new InvalidArgumentException('date should be xxxx/xx/xx');
        }

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

if (!function_exists('_mysql_update_enum')) {

    /**
     * @param string $table
     * @param string $column
     * @param array $newTypes
     */
    function _mysql_update_enum(string $table, string $column, array $newTypes)
    {
        DB::statement('ALTER TABLE' . $table . 'MODIFY' . "`$column`" . 'ENUM(' .
            "'" . implode("','", $newTypes) . "'" . ');');
    }
}

if (!function_exists('_random_saudi_mobile')) {
    function _random_saudi_mobile()
    {
        return '0504127' . random_int(100, 999);
    }
}

if (!function_exists('_current_guard')) {
    function _current_guard()
    {
        return collect(config('auth.guards'))
            ->keys()
            ->first(fn($guard) => auth()->guard($guard)->check());
    }
}

if (!function_exists('_current_auth')) {
    function _current_auth()
    {
        return auth(_current_guard());
    }
}
