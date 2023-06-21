<?php

namespace Dwikipeddos\PeddosLaravelTools\Traits;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasSingleImage
{
    use InteractsWithMedia;

    public string $defaultSingleImageName = "image";
    public string $defaultThumbnailName = "thumb";

    public string $defaultSingleImage = "https://www.pngkey.com/png/full/233-2332677_image-500580-placeholder-transparent.png";
    public string $defaultThumbnail = "https://www.pngkey.com/png/full/233-2332677_image-500580-placeholder-transparent.png";

    public function getSingleImageName()
    {
        return $this->singleImageName ?? $this->defaultSingleImageName;
    }

    public function getThumbnailName()
    {
        return $this->thumbnail ?? $this->defaultThumbnailName;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->getSingleImageName())
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion($this->getThumbnailName())
            ->fit(Manipulations::FIT_CONTAIN, 100, 100);
    }

    public function getSingleImage()
    {
        return $this->getFirstMedia($this->getSingleImageName())?->getUrl()
            ?? $this->defaultSingleImage;
    }
    public function getSingleImageThumbnail()
    {
        return $this->getFirstMedia($this->getSingleImageName())?->getUrl($this->getThumbnailName())
            ?? $this->defaultThumbnail;
    }

    public function addSingleImageFromRequest(?string $key = null)
    {
        $this->addMediaFromRequest($key ?? $this->getSingleImageName())->toMediaCollection($this->getSingleImageName());
    }
}
