<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $total = count($repo->findAll());
        $pages = ceil($total / $limit);
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findBy([],[], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * 
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Booking $booking){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La location a bien ete supprimee !"
        );

        return $this->redirectToRoute('admin_booking_index');
         
    }
}
