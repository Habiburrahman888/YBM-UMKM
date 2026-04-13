<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Log an activity.
     *
     * @param  string       $event       create|update|delete|login|logout|approve|reject|verify|toggle_status|export|...
     * @param  string       $description Human-readable description
     * @param  Model|null   $subject     The model that was affected
     * @param  array        $properties  ['old' => [...], 'new' => [...]]
     * @param  string       $logName     Group/channel name
     */
    public static function log(
        string $event,
        string $description,
        ?Model $subject = null,
        array  $properties = [],
        string $logName = 'default'
    ): ActivityLog {
        $user = auth()->user();

        return ActivityLog::create([
            'log_name'      => $logName,
            'causer_id'     => $user?->id,
            'causer_type'   => $user ? get_class($user) : null,
            'causer_name'   => $user?->username ?? $user?->email ?? 'System',
            'causer_role'   => $user?->role ?? 'system',
            'event'         => $event,
            'description'   => $description,
            'subject_type'  => $subject ? get_class($subject) : null,
            'subject_id'    => $subject?->getKey(),
            'subject_label' => static::resolveSubjectLabel($subject),
            'properties'    => $properties ?: null,
            'ip_address'    => request()->ip(),
            'user_agent'    => substr(request()->userAgent() ?? '', 0, 300),
        ]);
    }

    /**
     * Shortcut: log create event
     */
    public static function logCreate(Model $subject, string $description, array $newData = []): ActivityLog
    {
        return static::log('create', $description, $subject, ['new' => $newData], 'default');
    }

    /**
     * Shortcut: log update event (capture old vs new)
     */
    public static function logUpdate(Model $subject, string $description, array $old = [], array $new = []): ActivityLog
    {
        return static::log('update', $description, $subject, compact('old', 'new'), 'default');
    }

    /**
     * Shortcut: log delete event
     */
    public static function logDelete(string $description, string $subjectType, int $subjectId, string $subjectLabel, array $properties = []): ActivityLog
    {
        $log              = new ActivityLog();
        $log->log_name    = 'default';
        $log->causer_id   = auth()->id();
        $log->causer_type = auth()->user() ? get_class(auth()->user()) : null;
        $log->causer_name = auth()->user()?->username ?? auth()->user()?->email ?? 'System';
        $log->causer_role = auth()->user()?->role ?? 'system';
        $log->event       = 'delete';
        $log->description = $description;
        $log->subject_type  = $subjectType;
        $log->subject_id    = $subjectId;
        $log->subject_label = $subjectLabel;
        $log->properties  = $properties ?: null;
        $log->ip_address  = request()->ip();
        $log->user_agent  = substr(request()->userAgent() ?? '', 0, 300);
        $log->save();

        return $log;
    }

    /**
     * Shortcut: login / logout
     */
    public static function logAuth(string $event, $user): ActivityLog
    {
        $description = $event === 'login'
            ? "User {$user->username} berhasil login"
            : "User {$user->username} logout";

        $log              = new ActivityLog();
        $log->log_name    = 'auth';
        $log->causer_id   = $user->id;
        $log->causer_type = get_class($user);
        $log->causer_name = $user->username ?? $user->email;
        $log->causer_role = $user->role;
        $log->event       = $event;
        $log->description = $description;
        $log->ip_address  = request()->ip();
        $log->user_agent  = substr(request()->userAgent() ?? '', 0, 300);
        $log->save();

        return $log;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private static function resolveSubjectLabel(?Model $subject): ?string
    {
        if (!$subject) return null;

        // Try common label fields
        foreach (['nama_usaha', 'nama_unit', 'username', 'name', 'nama', 'title'] as $field) {
            if (isset($subject->$field)) {
                return $subject->$field;
            }
        }

        return class_basename($subject) . ' #' . $subject->getKey();
    }

    /**
     * Pluck only safe fields for logging (exclude sensitive data)
     */
    public static function safeAttributes(Model $model, array $exclude = ['password', 'remember_token']): array
    {
        return collect($model->getAttributes())
            ->except($exclude)
            ->toArray();
    }
}
