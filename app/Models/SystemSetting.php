<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'label',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            self::create([
                'key' => $key,
                'value' => (string) $value,
                'type' => 'string',
            ]);
        } else {
            $setting->update([
                'value' => (string) $value,
            ]);
        }
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory(string $category)
    {
        return self::where('category', $category)->get()->mapWithKeys(function ($setting) {
            return [$setting->key => self::castValue($setting->value, $setting->type)];
        });
    }

    /**
     * Check if employee file upload is allowed
     */
    public static function allowEmployeeFileUpload(): bool
    {
        return (bool) self::get('allow_employee_file_upload', false);
    }

    /**
     * Get application name
     */
    public static function appName(): string
    {
        return self::get('app_name', 'EHRMS');
    }

    /**
     * Get application short name
     */
    public static function appShortName(): string
    {
        return self::get('app_short_name', 'EHRMS');
    }

    /**
     * Get application logo path
     */
    public static function appLogo(): ?string
    {
        return self::get('app_logo', null);
    }

    /**
     * Get application tagline
     */
    public static function appTagline(): string
    {
        return self::get('app_tagline', 'Employee Human Resource Management System');
    }

    /**
     * Get organization name
     */
    public static function organizationName(): string
    {
        return self::get('organization_name', 'LGU Sablayan');
    }
}
