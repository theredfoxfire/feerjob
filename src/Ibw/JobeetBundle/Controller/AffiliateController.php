<?php
namespace Ibw\JobeetBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ibw\JobeetBundle\Entity\Affiliate;
use Ibw\JobeetBundle\Form\AffiliateType;
use Symfony\Component\HttpFoundation\Request;
use Ibw\JobeetBundle\Entity\Category;
 
class AffiliateController extends Controller
{
    public function newAction()
    {
        $entity = new Affiliate();
        $form = $this->createForm(new AffiliateType(), $entity);
 
        return $this->render('IbwJobeetBundle:Affiliate:affiliate_new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
     public function createAction(Request $request)
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(new AffiliateType(), $affiliate);
        $form->bind($request);
        $em = $this->getDoctrine()->getManager();
 
        if ($form->isValid()) {
 
            $formData = $request->get('affiliate');
            $affiliate->setUrl($formData['url']);
            $affiliate->setEmail($formData['email']);
            $affiliate->setIsActive(false);
 
            $em->persist($affiliate);
            $em->flush();
 
            return $this->redirect($this->generateUrl('ibw_affiliate_wait'));
        }
 
        return $this->render('IbwJobeetBundle:Affiliate:affiliate_new.html.twig', array(
            'entity' => $affiliate,
            'form'   => $form->createView(),
        ));
    }
    
    public function waitAction()
    {
        return $this->render('IbwJobeetBundle:Affiliate:wait.html.twig');
    }
}
