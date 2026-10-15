<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Faq;

class FaqService
{
    /**
     * Create a FAQ, or update it when one is given.
     *
     * @param  array{question: string, answer: string, sort_order: int|string, is_active: bool}  $data
     */
    public function save(?Faq $faq, array $data): Faq
    {
        $payload = [
            'question' => trim($data['question']),
            'answer' => trim($data['answer']),
            'sort_order' => (int) $data['sort_order'],
            'is_active' => (bool) $data['is_active'],
        ];

        if ($faq === null) {
            return Faq::create($payload);
        }

        $faq->update($payload);

        return $faq;
    }

    public function toggleActive(Faq $faq): void
    {
        $faq->update(['is_active' => ! $faq->is_active]);
    }

    public function delete(Faq $faq): void
    {
        $faq->delete();
    }

    /**
     * The sort order a new FAQ gets so that it appears last.
     */
    public function nextSortOrder(): int
    {
        return min(((int) Faq::max('sort_order')) + 1, 65535);
    }
}
