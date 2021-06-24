<?php

namespace App\Controller;

use App\Entity\HistoricQuestion;
use App\Entity\Post;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\HistoricQuestionRepository;
use App\Repository\PostRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiPostController extends AbstractController
{

    /**
     * Get all of Question entities
     * Return a JSON with Question entities
     * @Route("/api/getQuestions", name="api_get_question", methods={"GET"})
     */
    public function questions(QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->findAll();
        return $this->json($question, 200,[],['groups' => 'post:read']);
    }

    /**
     * Insert a new entity to database
     * Return the new Question entity stored in database
     * @Route("/api/postQuestion", name="api_post_question", methods={"POST"})
     */
    public function insert(Request $request, SerializerInterface $serializer , EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {

        try{

            $json_get = $request->getContent();

            $question = $serializer->deserialize($json_get, Question::class, 'json');
            $question->setCreatedAt(new \DateTime());

            $error = $validator->validate($question);
            if(count($error) > 0){
                return $this->json($error, 400);
            }

            $entityManager->persist($question);
            $entityManager->flush();

            return $this->json($question, 201, [], ['groups' => 'post:read']);


        }catch (NotEncodableValueException $exception){
            return $this->json([
                'status' => '400',
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Update a Question entity and save change into a new HistoricQuestion
     * Return the Question entity updated
     * @Route("/api/updateQuestion", name="api_update_question", methods={"POST"})
     */
    public function update(Request $request,QuestionRepository $questionRepository, SerializerInterface $serializer , EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {

        try{

            $json_get = $request->getContent();
            //normalization and encoding to json
            $questions = $serializer->deserialize($json_get, Question::class, 'json', ['attributes' => ['id','title', 'status']]);

            $question = $questionRepository->findOneBy(['id' => $questions->getId()]);

            //creation of a new instance of historic
            $historic = new HistoricQuestion();
            $historic->setOldTitle($question->getTitle());
            $historic->setNewTitle($questions->getTitle());
            $historic->setOldStatus($question->getStatus());
            $historic->setNewStatus($questions->getStatus());
            $historic->setQuestionId($question->getId());
            $historic->setChangedAt(new \DateTime());
            $error_historic = $validator->validate($historic);
            if(count($error_historic) > 0){
                return $this->json($error_historic, 400);
            }


            //update the existing question with new status an new title
            $question->setTitle($questions->getTitle());
            $question->setStatus($questions->getStatus());
            $question->setUpdatedAt(new \DateTime());





            $error = $validator->validate($question);
            if(count($error) > 0){
                return $this->json($error, 400);
            }

            $entityManager->persist($historic);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->json($question, 201, [], ['groups' => 'post:read']);


        }catch (NotEncodableValueException $exception){
            return $this->json([
                'status' => '400',
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Export HistoricQuestion entities into CSV
     * Return HistoricQuestion.csv file as attachment
     * @Route("/api/historicCSV", name="api_historic_CSV", methods={"GET"})
     */
    public function getCSV(HistoricQuestionRepository $historicRepository):Response
    {
        $historics = $historicRepository->findAll();
        //columns
        $result = array(array('id', 'question_id','old_title','new_title','old_status','new_status','changed_at'));

        foreach ($historics as $historic)
        {
            //echo $historic->getNewTitle();
            $changed_at = $historic->getChangedAt()->format('Y-m-d H:i:s');
            $lists = array(
                        //row
                        array(
                            $historic->getId(),
                            $historic->getQuestionId(),
                            $historic->getOldTitle(),
                            $historic->getNewTitle(),
                            $historic->getOldStatus(),
                            $historic->getNewStatus(),
                            $changed_at
                        ));
            $result = array_merge($result, $lists);

        }


        $fp = fopen('php://output', 'w');

        foreach ($result as $fields) {
            fputcsv($fp, $fields,',',chr(127));
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        //it's gonna output in a testing.csv file
        $response->headers->set('Content-Disposition', 'attachment; filename="HistoricQuestion.csv"');

        return $response;
    }

    /**
     * Export an entity into CSV by passing her name in a JSON field
     * Return [Entity Name].csv file as attachment
     * @Route("/api/entityCSV", name="api_entity_CSV", methods={"GET"})
     */
    public function getEntityCSV(Request $request,QuestionRepository $questionRepository, AnswerRepository $answerRepository, HistoricQuestionRepository $historicRepository):Response
    {
        $data = json_decode($request->getContent(), true);

        if($data['entity_name'] == 'Question')
        {
            $repository = $questionRepository->findAll();
            //columns
            $result = array(array('id', 'title','promoted','status','created_at','updated_at'));
            $filename = 'Question';

        }
        elseif ($data['entity_name'] == 'Answer')
        {
            $repository = $answerRepository->findAll();
            //columns
            $result = array(array('id', 'question_id','channel','body'));
            $filename = 'Answer';

        }
        elseif ($data['entity_name'] == 'HistoricQuestion')
        {
            $repository = $historicRepository->findAll();
            //columns
            $result = array(array('id', 'question_id','old_title','new_title','old_status','new_status','changed_at'));
            $filename = 'HistoricQuestion';
        }
        else
        {
            return $this->json([
                "status" => "400",
                "message" => "This entity doesn't exist"
            ], 400);
        }




        foreach ($repository as $entity)
        {
            if($data['entity_name'] == 'Question')
            {

                $create_date = $entity->getCreatedAt()->format('Y-m-d H:i:s');
                if( $entity->getUpdatedAt() != null) //if entity has been updated
                {
                    $updated_date = $entity->getUpdatedAt()->format('Y-m-d H:i:s');
                }
                if($entity->getPromoted())
                {
                    $promoted = 'true';
                }else $promoted = 'false';
                $lists = array(
                    //row
                    array(
                        $entity->getId(),
                        $entity->getTitle(),
                        $promoted,
                        $entity->getStatus(),
                        $create_date,
                        $updated_date
                    ));
                $result = array_merge($result, $lists);

            }
            elseif ($data['entity_name'] == 'Answer')
            {
                $lists = array(
                    //row
                    array(
                        $entity->getId(),
                        $entity->getQuestion()->getId(),
                        $entity->getChannel(),
                        $entity->getBody(),
                    ));
                $result = array_merge($result, $lists);

            }
            elseif ($data['entity_name'] == 'HistoricQuestion')
            {
                $changed_at = $entity->getChangedAt()->format('Y-m-d H:i:s');
                $lists = array(
                    //row
                    array(
                        $entity->getId(),
                        $entity->getQuestionId(),
                        $entity->getOldTitle(),
                        $entity->getNewTitle(),
                        $entity->getOldStatus(),
                        $entity->getNewStatus(),
                        $changed_at
                    ));
                $result = array_merge($result, $lists);
            }
        }


        $fp = fopen('php://output', 'w');

        foreach ($result as $fields) {
            fputcsv($fp, $fields,',',chr(127));
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        //it's gonna output in a testing.csv file
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'.csv"');

        return $response;
    }



    /**
     * Export an entity into CSV by passing her name in URL
     * Return [Entity Name].csv file as attachment
     * @Route("/api/entityCSV/{entity_name}", name="api_CSV_entity", methods={"GET"})
     */
    public function getCSVEntity(string $entity_name, Request $request,QuestionRepository $questionRepository, AnswerRepository $answerRepository, HistoricQuestionRepository $historicRepository):Response
    {
        if($entity_name == 'Question')
        {
            $repository = $questionRepository->findAll();
            //columns
            $result = array(array('id', 'title','promoted','status','created_at','updated_at'));
            $filename = 'Question';

        }
        elseif ($entity_name == 'Answer')
        {
            $repository = $answerRepository->findAll();
            //columns
            $result = array(array('id', 'question_id','channel','body'));
            $filename = 'Answer';

        }
        elseif ($entity_name == 'HistoricQuestion')
        {
            $repository = $historicRepository->findAll();
            //columns
            $result = array(array('id', 'question_id','old_title','new_title','old_status','new_status','changed_at'));
            $filename = 'HistoricQuestion';
        }
        else
        {
            return $this->json([
                "status" => "400",
                "message" => "This entity doesn't exist"
            ], 400);
        }




        foreach ($repository as $entity)
        {
            if($entity_name == 'Question')
            {

                $create_date = $entity->getCreatedAt()->format('Y-m-d H:i:s');
                $updated_date = $entity->getUpdatedAt()->format('Y-m-d H:i:s');
                if($entity->getPromoted())
                {
                    $promoted = 'true';
                }else $promoted = 'false';
                $lists = array(
                    //row
                    array(
                        $entity->getId(),
                        $entity->getTitle(),
                        $promoted,
                        $entity->getStatus(),
                        $create_date,
                        $updated_date
                    ));
                $result = array_merge($result, $lists);

            }
            elseif ($entity_name == 'Answer')
            {
                $lists = array(
                    //row
                    array(
                        $entity->getId(),
                        $entity->getQuestion()->getId(),
                        $entity->getChannel(),
                        $entity->getBody(),
                    ));
                $result = array_merge($result, $lists);

            }
            elseif ($entity_name == 'HistoricQuestion')
            {
                $changed_at = $entity->getChangedAt()->format('Y-m-d H:i:s');
                $lists = array(
                    //row
                    array(
                        $entity->getId(),
                        $entity->getQuestionId(),
                        $entity->getOldTitle(),
                        $entity->getNewTitle(),
                        $entity->getOldStatus(),
                        $entity->getNewStatus(),
                        $changed_at
                    ));
                $result = array_merge($result, $lists);
            }
        }


        $fp = fopen('php://output', 'w');

        foreach ($result as $fields) {
            fputcsv($fp, $fields,',',chr(127));
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');

        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'.csv"');

        return $response;
    }

}
