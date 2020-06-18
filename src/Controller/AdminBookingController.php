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
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
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
