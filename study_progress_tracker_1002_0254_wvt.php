<?php
// 代码生成时间: 2025-10-02 02:54:38
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Validation;
use Phalcon\Validation\ValidationInterface;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\StringLength;

class StudyProgress extends Model
{
    /**
     * @Primary
     * @Column(type="integer")
     * @Identity
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $user_id;

    /**
     * @Column(type="string")
     */
    protected $course_id;

    /**
     * @Column(type="integer")
     */
    protected $progress;

    /**
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("study_progress");
    }

    /**
     * Creates a new study progress record.
     *
     * @param array $data
     * @return bool
     */
    public function createProgress(array $data): bool
    {
        try {
            $this->user_id = $data['user_id'] ?? null;
            $this->course_id = $data['course_id'] ?? null;
            $this->progress = $data['progress'] ?? 0;
            $this->created_at = date("Y-m-d H:i:s");

            // Validate data
            $validator = new Validation();
            $validator->add(
                "user_id",
                new PresenceOf([
                    "message" => "User ID is required."
                ])
            );
            $validator->add(
                "course_id",
                new PresenceOf([
                    "message" => "Course ID is required."
                ])
            );
            $validator->add(
                "progress",
                new PresenceOf([
                    "message" => "Progress is required."
                ])
            );

            /**
             * Check if validation failed
             *
             * @var ValidationInterface $validation
             */
            $validation = $validator->validate($data);
            if ($validation->count() > 0) {
                // Return the error
                foreach ($validation->getMessages() as $message) {
                    $this->appendMessage($message);
                }
                return false;
            }

            // Create record
            return $this->save();
        } catch (\Exception $e) {
            // Handle exception
            $this->appendMessage(new Message($e->getMessage()));
            return false;
        }
    }

    /**
     * Updates an existing study progress record.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateProgress(array $data, int $id): bool
    {
        try {
            // Find the record
            $this->find($id);
            if (!$this->id) {
                $this->appendMessage(new Message("Study progress record not found."));
                return false;
            }

            // Update record
            $this->user_id = $data['user_id'] ?? $this->user_id;
            $this->course_id = $data['course_id'] ?? $this->course_id;
            $this->progress = $data['progress'] ?? $this->progress;
            $this->created_at = date("Y-m-d H:i:s");

            // Validate data
            $validator = new Validation();
            $validator->add(
                "user_id",
                new PresenceOf([
                    "message" => "User ID is required."
                ])
            );
            $validator->add(
                "course_id",
                new PresenceOf([
                    "message" => "Course ID is required."
                ])
            );
            $validator->add(
                "progress",
                new PresenceOf([
                    "message" => "Progress is required."
                ])
            );

            /**
             * Check if validation failed
             *
             * @var ValidationInterface $validation
             */
            $validation = $validator->validate($data);
            if ($validation->count() > 0) {
                // Return the error
                foreach ($validation->getMessages() as $message) {
                    $this->appendMessage($message);
                }
                return false;
            }

            // Update record
            return $this->save();
        } catch (\Exception $e) {
            // Handle exception
            $this->appendMessage(new Message($e->getMessage()));
            return false;
        }
    }

    /**
     * Deletes a study progress record.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProgress(int $id): bool
    {
        try {
            // Find the record
            $this->find($id);
            if (!$this->id) {
                $this->appendMessage(new Message("Study progress record not found."));
                return false;
            }

            // Delete record
            return $this->delete();
        } catch (\Exception $e) {
            // Handle exception
            $this->appendMessage(new Message($e->getMessage()));
            return false;
        }
    }
}
