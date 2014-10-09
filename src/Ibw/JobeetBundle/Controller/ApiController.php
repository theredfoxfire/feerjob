<?php
namespace Ibw\JobeetBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ibw\JobeetBundle\Entity\Affiliate;
use Ibw\JobeetBundle\Entity\Job;
use Ibw\JobeetBundle\Repository\AffiliateRepository;
 
class ApiController extends Controller
{
    public function listAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
 
        $jobs = array();
 
        $rep = $em->getRepository('IbwJobeetBundle:Affiliate');
        $affiliate = $rep->getForToken($token);
 
        if(!$affiliate) {
            throw $this->createNotFoundException('This affiliate account does not exist!');
        }
 
        $rep = $em->getRepository('IbwJobeetBundle:Job');
        $active_jobs = $rep->getActiveJobs(null, null, null, $affiliate->getId());
 
        foreach ($active_jobs as $job) {
            $jobs[$this->get('router')->generate('ibw_job_show', array('company' => $job->getCompanySlug(), 'location' => $job->getLocationSlug(), 'id' => $job->getId(), 'position' => $job->getPositionSlug()), true)] = $job->asArray($request->getHost());
        }
 
        $format = $request->getRequestFormat();
        $jsonData = json_encode($jobs);
 
        if ($format == "json") {
            $headers = array('Content-Type' => 'application/json');
            $response = new Response($jsonData, 200, $headers);
 
            return $response;
        }
 
        return $this->render('IbwJobeetBundle:Api:jobs.' . $format . '.twig', array('jobs' => $jobs));  
    }
}
