<?php

namespace App\Helpers;

class DocumentHelper
{
    /**
     * Obtiene la descripción legible de un tipo de documento
     *
     * @param string|null $type
     * @return string
     */
    public static function getDocumentTypeName(?string $type): string
    {
        return match ($type) {
            'cedula_ciudadania' => 'Cédula de Ciudadanía',
            'tarjeta_identidad' => 'Tarjeta de Identidad',
            'registro_civil' => 'Registro Civil',
            'cedula_extranjeria' => 'Cédula de Extranjería',
            'pasaporte' => 'Pasaporte',
            'pep' => 'PEP',
            'ppt' => 'PPT',
            default => 'No especificado',
        };
    }

    /**
     * Obtiene el formato abreviado de un tipo de documento
     * 
     * @param string|null $type
     * @return string
     */
    public static function getDocumentTypeAbbr(?string $type): string
    {
        return match ($type) {
            'cedula_ciudadania' => 'CC',
            'tarjeta_identidad' => 'TI',
            'registro_civil' => 'RC',
            'cedula_extranjeria' => 'CE',
            'pasaporte' => 'PAS',
            'pep' => 'PEP',
            'ppt' => 'PPT',
            default => 'N/A',
        };
    }
}
