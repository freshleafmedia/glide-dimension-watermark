<?php declare(strict_types=1);

namespace Freshleafmedia\DimensionWatermark;

use Intervention\Image\AbstractFont;
use Intervention\Image\Image;
use League\Glide\Manipulators\BaseManipulator;

/**
 * @property ?string $dwm
 */
class DimensionWatermark extends BaseManipulator
{
    public function run(Image $image): Image
    {
        if ($this->dwm === null) {
            return $image;
        }

        for ($offsetX = -2; $offsetX <= 2; $offsetX++) {
            for ($offsetY = -2; $offsetY <= 2; $offsetY++) {
                $image->text(
                    $image->width() . 'x' . $image->height(),
                    ($image->width() / 2) + $offsetX,
                    ($image->height() / 2) + $offsetY,
                    function (AbstractFont $font) use ($image): void {
                        $font->file(__DIR__ . '/../fonts/OpenSans-Regular-webfont.ttf');
                        $font->size($this->calculateBestFontSize($image, $font, 60));
                        $font->align('center');
                        $font->valign('center');
                        $font->color('#000');
                    });
            }
        }

        $image->text(
            $image->width() . 'x' . $image->height(),
            $image->width() / 2,
            $image->height() / 2,
            function (AbstractFont $font) use ($image): void {
                $font->file(__DIR__ . '/../fonts/OpenSans-Regular-webfont.ttf');
                $font->size($this->calculateBestFontSize($image, $font, 60));
                $font->align('center');
                $font->valign('center');
                $font->color('#FFF');
            }
        );

        return $image;
    }

    protected function calculateBestFontSize(Image $image, AbstractFont $font, int $max, int $min = 5): int
    {
        $paddingPx = 30;
        $font->size($max);

        while ($font->getBoxSize()['width'] > ($image->width() - $paddingPx) || $font->getBoxSize()['height'] > ($image->height() - $paddingPx)) {
            $size = $font->getSize() - 5;

            if ($size <= $min) {
                break;
            }

            $font->size($size);
        }

        return $font->size;
    }
}
