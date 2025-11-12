<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ImageUploadService
{
    private ValidatorInterface $validator;
    private string $coversDir;

    public function __construct(ValidatorInterface $validator, string $coversDir
    ){
        $this->validator = $validator;
        $this->coversDir = $coversDir;
    }

    public function UploadCover(UploadedFile $file): ?string
    {

        $violations = $this->validator->validate(
            $file,
            new Image([
                'maxSize' => '5M',
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'detectCorrupted' => true,
            ])
        );

        if ($violations->count() > 0) {
            return null;
        }

        $mime = $file->getMimeType();
        $ext  = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            default      => 'bin',
        };

        $fileName = bin2hex(random_bytes(8)).'.'.$ext;

        $file->move($this->coversDir, $fileName);

        return $fileName;
    }
}

