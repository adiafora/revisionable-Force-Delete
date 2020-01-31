<?php

namespace Adiafora\RevisionableForceDelete;

use \Venturecraft\Revisionable\Revisionable;

trait RevisionableForceDeleteTrait
{
    /**
     * Boot method
     *
     */
    public static function bootRevisionableForceDeleteTrait()
    {
        static::deleted(function ($model) {
            $model->postForceDelete();
        });
    }

    /**
     * If forcedeletes are enabled, set the value created_at of model to null
     *
     * @return void|bool
     */
    public function postForceDelete()
    {
        if (empty($this->revisionForceDeleteEnabled)) {
            return false;
        }

        if ((!isset($this->revisionEnabled) || $this->revisionEnabled)
            && (($this->isSoftDelete() && $this->isForceDeleting()) || !$this->isSoftDelete())) {

            $revisions[] = array(
                'revisionable_type' => $this->getMorphClass(),
                'revisionable_id' => $this->getKey(),
                'key' => self::CREATED_AT,
                'old_value' => $this->{self::CREATED_AT},
                'new_value' => null,
                'user_id' => $this->getSystemUserId(),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            );

            $revision = Revisionable::newModel();
            \DB::table($revision->getTable())->insert($revisions);
            \Event::dispatch('revisionable.forceDeleted', array('model' => $this, 'revisions' => $revisions));
        }
    }
}