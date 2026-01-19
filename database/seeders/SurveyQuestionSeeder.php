<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurveyQuestion;

class SurveyQuestionSeeder extends Seeder
{
    /**
     * Seed default survey questions based on LGU Sablayan forms
     */
    public function run(): void
    {
        $defaultQuestions = [
            // Question 1: Training Programs Selection (Special Type)
            [
                'question_text' => 'Which training programs would you like to attend this year? (Select all that apply)',
                'question_type' => 'training_programs',
                'options' => null, // Loaded from training_programs table
                'help_text' => 'Please select training programs that align with your development needs and job responsibilities.',
                'is_default' => true,
            ],

            // Question 2: Employment Status/Needs
            [
                'question_text' => 'What are your current employment development needs?',
                'question_type' => 'textarea',
                'options' => null,
                'help_text' => 'Please describe any skills, knowledge, or competencies you need to develop for your current role.',
                'is_default' => true,
            ],

            // Question 3: Performance Gaps
            [
                'question_text' => 'List performance gaps to be addressed and/or learning and development interventions needed',
                'question_type' => 'textarea',
                'options' => null,
                'help_text' => 'Identify areas where you need improvement or additional training.',
                'is_default' => true,
            ],

            // Question 4: Operational Objectives
            [
                'question_text' => 'Link to specific operational objective(s) of Division/Section/Office',
                'question_type' => 'textarea',
                'options' => null,
                'help_text' => 'Which Division/Section/Office objectives, needs and priorities need to be addressed through this training?',
                'is_default' => true,
            ],

            // Question 5: Personal Learning Objectives
            [
                'question_text' => 'State your personal goals or learning objectives',
                'question_type' => 'textarea',
                'options' => null,
                'help_text' => 'What do you personally hope to achieve through training this year?',
                'is_default' => true,
            ],

            // Question 6: Preferred Schedule
            [
                'question_text' => 'What is your preferred training schedule?',
                'question_type' => 'radio',
                'options' => [
                    'Weekday Morning (8AM-12PM)',
                    'Weekday Afternoon (1PM-5PM)',
                    'Weekend (Saturday/Sunday)',
                    'Any time',
                ],
                'help_text' => 'Select the schedule that works best for you.',
                'is_default' => true,
            ],

            // Question 7: Preferred Format
            [
                'question_text' => 'What is your preferred training format?',
                'question_type' => 'radio',
                'options' => [
                    'In-person/Face-to-face',
                    'Online/Virtual',
                    'Hybrid (Mix of both)',
                    'No preference',
                ],
                'help_text' => 'Choose the learning format you are most comfortable with.',
                'is_default' => true,
            ],

            // Question 8: Training Priority Level
            [
                'question_text' => 'How critical is this training to your current job performance?',
                'question_type' => 'scale',
                'options' => null,
                'help_text' => 'Rate from 1 (not critical) to 5 (very critical)',
                'is_default' => true,
            ],

            // Question 9: Willingness to Share Knowledge
            [
                'question_text' => 'After completing the training, would you be willing to share your learnings with your colleagues?',
                'question_type' => 'radio',
                'options' => [
                    'Yes, through formal presentation',
                    'Yes, through informal sharing',
                    'Yes, both formal and informal',
                    'Not sure',
                    'No',
                ],
                'help_text' => 'Knowledge sharing helps maximize training impact across the organization.',
                'is_default' => true,
            ],

            // Question 10: Suggested Additional Topics
            [
                'question_text' => 'Are there any other training topics not listed above that you would like to suggest?',
                'question_type' => 'textarea',
                'options' => null,
                'help_text' => 'Please provide specific training topics or areas you think would benefit you and your colleagues.',
                'is_default' => true,
            ],

            // Question 11: Support Needed
            [
                'question_text' => 'What support or resources would you need to successfully complete the training?',
                'question_type' => 'checkbox',
                'options' => [
                    'Time off from regular duties',
                    'Financial assistance (registration fees)',
                    'Travel allowance',
                    'Training materials/equipment',
                    'Manager/supervisor approval',
                    'Other (please specify)',
                ],
                'help_text' => 'Select all types of support you would need.',
                'is_default' => true,
            ],

            // Question 12: Years in Current Position
            [
                'question_text' => 'How many years have you been in your current position?',
                'question_type' => 'number',
                'options' => null,
                'help_text' => 'This helps us understand your experience level.',
                'is_default' => true,
            ],

            // Question 13: Supervisor Support
            [
                'question_text' => 'Has this training plan been discussed with your immediate supervisor?',
                'question_type' => 'radio',
                'options' => [
                    'Yes, and they support it',
                    'Yes, but pending approval',
                    'Not yet discussed',
                    'Not applicable',
                ],
                'help_text' => 'Supervisor support is important for training approval.',
                'is_default' => true,
            ],

            // Question 14: Additional Comments/Remarks
            [
                'question_text' => 'Additional comments, suggestions, or remarks',
                'question_type' => 'textarea',
                'options' => null,
                'help_text' => 'Any other information you would like to share about your training needs.',
                'is_default' => false, // Optional
            ],
        ];

        foreach ($defaultQuestions as $questionData) {
            SurveyQuestion::create($questionData);
        }

        $this->command->info('✅ Created ' . count($defaultQuestions) . ' default survey questions');
    }
}
