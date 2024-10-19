<?php

use Illuminate\Support\Facades\File;

if (! function_exists('vite_manifest')) {
  /**
   * Get the path of the Vite manifest file.
   *
   * @return string
   */
  function vite_manifest()
  {
    // Atualizar o caminho para procurar no diretório `.vite`
    return public_path('build/.vite/manifest.json');
  }
}

/**
 * Helper to return asset path from the Vite manifest.
 *
 * @param string $file
 * @return string
 */
function vite_asset($file)
{
  $manifestPath = vite_manifest();

  if (File::exists($manifestPath)) {
    $manifest = json_decode(File::get($manifestPath), true);

    if (isset($manifest[$file])) {
      return asset('build/' . $manifest[$file]['file']);
    }
  }

  // Fallback para asset se o manifest não for encontrado
  return asset($file);
}
