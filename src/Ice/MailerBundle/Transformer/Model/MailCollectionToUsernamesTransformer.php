<?php
namespace Ice\MailerBundle\Transformer\Model;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ice\ExternalUserBundle\Entity\User;
use Ice\MailerBundle\Entity\Mail;
use Doctrine\Common\Collections\ArrayCollection;

class MailCollectionToUsernamesTransformer implements DataTransformerInterface
{
    /** @var ObjectManager */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param  Mail[]|ArrayCollection|null $mails
     * @return array|string|null
     */
    public function transform($mails)
    {
        if (null === $mails) {
            return null;
        }

        $userNames = [];

        foreach ($mails as $mail) {
            $userNames[] = $mail->getRecipient()->getUsername();
        }

        return $userNames;
    }

    /**
     *
     * @param  array|string $data
     * @return User[]|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($data)
    {
        if ($data === null) {
            return null;
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        foreach($data as $user) {
            if (!is_string($user)) {
                throw new TransformationFailedException(sprintf(
                    'Usernames must be strings, %s given',
                    gettype($data)
                ));
            }
        }

        //We have an array of strings

        /** @var EntityRepository $userRepository */
        $userRepository = $this->om->getRepository('IceExternalUserBundle:User');
        $qb = $userRepository->createQueryBuilder('User');

        /** @var QueryBuilder $qb */
        $qb->select(array('u')) // string 'u' is converted to array internally
            ->from('IceExternalUserBundle:User', 'u')
            ->where($qb->expr()->in('u.username', ':usernames'))
        ;

        $users = $qb->getQuery()->execute(['usernames'=>$data]);

        $mails = [];
        foreach ($users as $user) {
            $mails[] = (new Mail())->setRecipient($user);
        }

        return $mails;
    }
}