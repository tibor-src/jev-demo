<?php

namespace App\Console\Commands;

use App\Jev\QuestionClassifier;
use Illuminate\Console\Command;

class AskJevCommand extends Command
{
    protected $signature = 'jev:ask {text : Text for Jev to judge}';

    protected $description = 'Ask Jev, through the OpenRouter provider, whether the text is a question';

    public function handle(QuestionClassifier $classifier): int
    {
        $text = $this->argument('text');

        if (! is_string($text) || blank($text)) {
            $this->error('Provide some text for Jev to judge.');

            return self::INVALID;
        }

        $result = $classifier->classify($text);

        if (! $result->succeeded()) {
            $this->error($result->error);

            return self::FAILURE;
        }

        $this->info('Probability this is a question: '.$result->probability);

        return self::SUCCESS;
    }
}
