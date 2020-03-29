<?php
require_once('VideoCreator.php');
$images = array(
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-yuhrxxdplw2k58je.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-n2runth4jpme75cx.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-iswcbjbcmddoonw0.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-258mhinwx5rb1l86.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-mj7fpi1xb6kye30z.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-7syobjkm4tn3hv8l.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-v2kvm955643g2m6h.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-xzcyvanimm7gm3sx.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-wy1l9f8s45avu2oq.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-49lz6suj4c1r10aq.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-r0wgzer9bnfuybat.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-18ehdovmobg7xfou.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-iv3mm1aj42shfib4.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-qr85omsqzi9eoul7.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-902rusnf26voaw08.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-hewig1bsk2bdm3vt.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-ret1gigbuc7ppxif.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-79qkyqofpoihfpfl.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-8zrx4ubzyypm3yj6.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-uvvscn6v17dial1l.jpeg",
    "https://d3au0sjxgpdyfv.cloudfront.net/s-1769685-wyrk8rbsy5wbfbnn.jpeg"
    );

$creator = new VideoCreator($images, 60, 4, 800, 450, 1);
echo $creator->generateSlideShow('output.mp4');
