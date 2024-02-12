<?php

namespace WordPress\Blueprints\Steps\Unzip;

use WordPress\Blueprints\Steps\BaseStep;
use \ZipArchive;

class UnzipStep extends BaseStep {
    
    public function __construct(
        public UnzipStepInput $input
    ) {
        parent::__construct();
    }

    public static function getInputClass(): string {
        return UnzipStepInput::class;
    }

    public function execute() {
        return '';
        $zipPath = temp_path('temp.zip');
        $this->input->zipFile->saveTo($zipPath);

        try {
            if (!is_dir($this->input->toPath)) {
                mkdir($this->input->toPath, 0777, true);
            }
            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== TRUE) {
                throw new \Exception('Failed to open zip file');
            }
            $zip->extractTo($this->input->toPath);
            $zip->close();
            chmod($this->input->toPath, 0777);
        } finally {
            unlink($zipPath);
        }
    }
}