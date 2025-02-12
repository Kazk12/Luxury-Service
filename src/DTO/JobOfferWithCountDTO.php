<?php
namespace App\DTO;

class JobOfferWithCountDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $job_title,
        public readonly float $salary,
        public readonly \DateTimeInterface $created_at,
        public readonly string $description,
        public readonly string $job_category,
        public readonly string $job_category_slug
    ) {
    }
}