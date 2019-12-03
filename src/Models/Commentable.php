<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

interface Commentable
{
    public function getId(): CommentableId;
    public function getRootId(): CommentableId;
}
